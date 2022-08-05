# Lambda Functions for Hansefit Pimcore

## Deployment

The stacks are automatically deployed by an github action defined in .github/github-actions-cdk-deploy.yaml. The action checks which files have changed and only deploys those stacks with have changes. Nevertheless CDK creates a Cloudformation Changeset before deploying a stack. Only if there are changes detected in the changeset CDK will actually start a deployment. The github action is triggerd by an github "push" action (this includes merges from pull requests).

It is possible to force a redeploy of all stacks by adding the string "[force_redeploy]" to a commit message. This will ignore the result of the file change filter of the github action and starts a CDK deploy command for each stack. But also in this case CDK itself will only redeploy if there is a change detected in the Cloudformation Changeset created during deployment.

If there are any changes at values in Parameter Store (see below) it might be neccessary to redeploy a stack. In this case the stack should be deleted completely first by using (example for dev environemtn)

```bash
DEPLOYMENT_ENV=DEV npx cdk destroy -a 'npx ts-node --prefer-ts-exts bin/<bin-file>.ts' --profile=hansefit-dev
```

and then redeployed from the command line by using the local deployment command (see the description of each stack below).
## Stacks

### (1) pimcore-translation-api-stack

Local deployment command: 
```bash
DEPLOYMENT_ENV=<DEV|TEST|PROD> npx cdk destroy -a 'npx ts-node --prefer-ts-exts bin/<bin-file>.ts' --profile=<local aws profile of hansefit account>
```

Defines a lambda function which handles requests to deepl and po API.

#### Usage of pimcore-translation-api-stack

Description of the routes

The Endpoint URL is maintained in the AWS Parameter Store. The name of the parameter is "/api-gateway/translation-api/customEndpoint".

**(a) GET/deepl?sourceLang=\<iso-code\>&targetLang=\<iso-code\>&text=\<base 64 encoded string of text to be translated\> => JWT Token required (Company, Partner, Trainierende)**

Returns the translated text from deepl API.

**(b) GET/iam/deepl?sourceLang=\<iso-code\>&targetLang=\<iso-code\>&text=\<base 64 encoded string of text to be translated\> => All principals from hansefit account have access through iam policy**

Returns the translated text from deepl API.

**(c) GET/po?language=\<iso-code\>**

Returns the terms from po in the requested language.

## Parameters in Parameter Store

These parameters must be set up in AWS Parameter Store before deployment ot the stacks. See in the list at each parameter (below) which stacks use the specific parameter and whether or not a redeployment of the stack is neccessary after changing the parameter value.

- /api-gateway/translation-api/customEndpoint = translation.<dev|test|prod>.hansefit.de
  - pimcore-translation-api-stack: YES (Redeployment required)

---
**NOTE**

If the deepl or po api key is changed it must be changed in the stack (constant at the beginning of the code) and the stack has to be redeployed.

---
