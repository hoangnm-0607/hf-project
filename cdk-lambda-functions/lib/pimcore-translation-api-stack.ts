/**
 * Stack creates the translation api.
 */
import { Duration, Stack, StackProps } from 'aws-cdk-lib';
import { AuthorizationType, CognitoUserPoolsAuthorizer, DomainName, EndpointType, LambdaIntegration, RestApi, SecurityPolicy } from 'aws-cdk-lib/aws-apigateway';
import { Certificate, CertificateValidation } from 'aws-cdk-lib/aws-certificatemanager';
import { IUserPool, UserPool, IUserPoolClient, UserPoolClient } from 'aws-cdk-lib/aws-cognito';
import { AccountPrincipal, AnyPrincipal, PolicyDocument, PolicyStatement } from 'aws-cdk-lib/aws-iam';
import { Runtime } from 'aws-cdk-lib/aws-lambda';
import { NodejsFunction } from 'aws-cdk-lib/aws-lambda-nodejs';
import { HostedZone, ARecord, RecordTarget } from 'aws-cdk-lib/aws-route53';
import { ApiGateway, ApiGatewayv2DomainProperties } from 'aws-cdk-lib/aws-route53-targets';
import { ParameterTier, StringParameter } from 'aws-cdk-lib/aws-ssm';
import { Construct } from 'constructs';

enum DEPLOYMENT_ENVIRONMENTS {
  DEV = "DEV",
  TEST = "TEST",
  PROD = "PROD"
};

const API_KEYS = {
  DEEPL_API_KEY: "d894ea37-e54b-24cd-4c23-b844a0d103b7:fx",
  PO_API_TOKEN: "cd9f4a0f5ec2c4b1f21d702990aa50e3",
};

export class PimcoreTranslationApiStack extends Stack {
  private stage: string;

  constructor(scope: Construct, id: string, props?: StackProps) {
    super(scope, id, props);

    // Node environment variable DEPLOYMENT_ENV must be set in deployment command to either DEV, TEST or PROD
    if (!process.env.DEPLOYMENT_ENV || !(Object.values(DEPLOYMENT_ENVIRONMENTS) as string[]).includes(process.env.DEPLOYMENT_ENV)) {
      throw new Error("Please set DEPLOYMENT_ENV environment variable to DEV, TEST or PROD.");
    }
    this.stage = process.env.DEPLOYMENT_ENV;

    // Get the user pools
    const customerUserPoolId = StringParameter.valueForStringParameter(this, "/cognito/userpool/customer");
    const customerUserPool: IUserPool = UserPool.fromUserPoolId(this, `UserPool_${this.stackName}-customer`, customerUserPoolId);
    const partnerUserPoolId = StringParameter.valueForStringParameter(this, "/cognito/userpool/partner");
    const partnerUserPool: IUserPool = UserPool.fromUserPoolId(this, `UserPool_${this.stackName}-partner`, partnerUserPoolId);
    const trainierendUserPoolId = StringParameter.valueForStringParameter(this, "/cognito/userpool/trainierende");
    const trainierendeUserPool: IUserPool = UserPool.fromUserPoolId(this, `UserPool_${this.stackName}-trainierende`, trainierendUserPoolId);

    // Get parameters for custom domain
    const hostedZoneName = StringParameter.valueForStringParameter(this, "/route53/env-zone-name");
    const hostedZoneId = StringParameter.valueForStringParameter(this, "/route53/env-zone-id");
    const domainEndpoint = StringParameter.valueForStringParameter(this, "/api-gateway/translation-api/customEndpoint");

    // Get reference to hosted zone
    const zone = HostedZone.fromHostedZoneAttributes(this, "pimcore-translation-api-hosted-zone", {
      zoneName: hostedZoneName,
      hostedZoneId: hostedZoneId,
    });

    // Create new certificate for domain endpoint
    const cert = new Certificate(this, "pimcore-translation-api-certificate", {
      domainName: domainEndpoint,
      validation: CertificateValidation.fromDns(zone),
    });

    // Create the domain
    // const domainName = new DomainName(this, "pimcore-translation-api-domain-name", {
    //   domainName: domainEndpoint,
    //   certificate: cert,
    // });

    // Create lambda function
    const lambdaFunction = this.createTranslationApiLambdaFunction();

    // Create the API Gateway
    const api = this.createRestApi(lambdaFunction, [customerUserPool, partnerUserPool, trainierendeUserPool], domainEndpoint, cert);

    // Create ARecord in Route53 for custom domain
    new ARecord(this, "pimcore-translation-api-a-record", {
      zone: zone,
      recordName: "translation",
      target: RecordTarget.fromAlias(new ApiGateway(api)),
    });
  }

  /**
   * Create the lambda function which handles the api gateway requests.
   *
   * @returns NodejsFunction
   */
  createTranslationApiLambdaFunction = (): NodejsFunction => {
    // Parameters of lambda function
    let lambdaParams: any = {
      functionName: `${this.stage}-pimcore-translation-api`,
      entry: "src/pimcore-translation-api/handler.ts",
      handler: "handler",
      runtime: Runtime.NODEJS_14_X,
      timeout: Duration.seconds(60),
      memorySize: 512,
      retryAttempts: 0,
      environment: {
        DEEPL_API_KEY: API_KEYS.DEEPL_API_KEY,
        PO_API_TOKEN: API_KEYS.PO_API_TOKEN
      },
    };

    // create the lambda function
    return new NodejsFunction(this, "pimcore-translation-api-function", lambdaParams);
  };

  /**
   * Creates the api gateway resources.
   * 
   * @param lambdaFunction lambda function handler
   * @param userPools user pools which should be authorized to access deepl api
   * @param domainEndpoint custom domain endpoint url for api
   * @param cert ssl certificate for api
   * @returns RestApi
   */
  createRestApi = (lambdaFunction: NodejsFunction, userPools: IUserPool[], domainEndpoint: string, cert: Certificate): RestApi => {
    const authorizer = new CognitoUserPoolsAuthorizer(this, "pimcore-translation-api-cognito-authorizer", {
      cognitoUserPools: userPools,
    });

    const apiPolicy = new PolicyDocument({
      statements: [
        new PolicyStatement({
          actions: ["execute-api:Invoke"],
          principals: [
            new AnyPrincipal(),            
          ],
          resources: ["execute-api:/*/GET/deepl", "execute-api:/*/GET/po"],
        }),
        new PolicyStatement({
          actions: ["execute-api:Invoke"],
          principals: [            
            new AccountPrincipal(this.account),            
          ],
          resources: ["execute-api:/*/GET/iam/deepl"],
        }),
      ],
    });
    
    const api = new RestApi(this, "pimcore-translation-api-gateway", {
      endpointConfiguration: {
        types: [EndpointType.REGIONAL],
      },
      domainName: {
        domainName: domainEndpoint,
        certificate: cert,
        endpointType: EndpointType.REGIONAL,
        securityPolicy: SecurityPolicy.TLS_1_2,
      },
      disableExecuteApiEndpoint: true,
      // 👇 enable CORS
      defaultCorsPreflightOptions: {
        allowHeaders: ["Content-Type", "X-Amz-Date", "Authorization", "X-Api-Key"],
        allowMethods: ["OPTIONS", "GET"],
        allowCredentials: true,
        allowOrigins: ["*"],
      },      
      policy: apiPolicy,
    });

    // add the /deepl ressource
    let resource = api.root.addResource("deepl");

    // integrate GET /deepl with lambda function
    resource.addMethod("GET", new LambdaIntegration(lambdaFunction, { proxy: true }), {
      authorizationType: AuthorizationType.COGNITO,
      authorizer: authorizer,
    });

    // add the /po ressource
    resource = api.root.addResource("po");

    // integrate GET /po with lambda function. Po ressource is publicily accessible.
    resource.addMethod("GET", new LambdaIntegration(lambdaFunction, { proxy: true }), {});

    // Create the iam route for deepl
    resource = api.root.addResource("iam").addResource("deepl");

    // Allow iam access to deepl iam route
    resource.addMethod("GET", new LambdaIntegration(lambdaFunction, {proxy: true}), {});

    new StringParameter(this, "pimcore-translation-api", {
      description: "Url of translation API Endpoint",
      parameterName: "/api-gateway/translation-api/endpoint",
      stringValue: api.url!,
      tier: ParameterTier.STANDARD,
    });
    return api;
  };
}
