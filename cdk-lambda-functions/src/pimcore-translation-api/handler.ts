import { APIGatewayProxyEvent, APIGatewayProxyResult } from "aws-lambda";
import axios from "axios";

/**
 * Handler for translation api. Handles requests to deepl and po translations.
 * 
 * @param event APIGatewayProxyEvent
 * @param context event context
 * @returns APIGatewayProxyResult 
 */
export const handler = async (event: APIGatewayProxyEvent, context: any = {}): Promise<APIGatewayProxyResult> => {
  console.log(JSON.stringify(event, null, 2));
  let response: APIGatewayProxyResult;
  
  try {
    if (event.httpMethod === "GET" && (event.resource === "/deepl" || event.resource === "/iam/deepl")) {
      const { text, sourceLang, targetLang } = event.queryStringParameters as { text: string; sourceLang: string; targetLang: string };
      const b64Buffer = Buffer.from(text, "base64");
      const encodedText = encodeURIComponent(b64Buffer.toString("utf8"));

      const deeplResponse = await axios.get(
        `https://api-free.deepl.com/v2/translate?auth_key=${process.env.DEEPL_API_KEY}&text=${encodedText}&source_lang=${sourceLang}&target_lang=${targetLang}`
      );
      response = {
        headers: {
          "Access-Control-Allow-Methods": "*",
          "Access-Control-Allow-Origin": "*",
          "Access-Control-Allow-Headers": "*",
        },
        statusCode: 200,
        body: JSON.stringify(deeplResponse.data),
      };

      return response;
    } else if (event.httpMethod === "GET" && event.resource === "/po") {
      const language = event.queryStringParameters?.language || "de";
      const params = new URLSearchParams();
      params.append("api_token", process.env.PO_API_TOKEN as string);
      params.append("id", "473959");
      params.append("language", language);
      const poResponse = await axios.post(`https://api.poeditor.com/v2/terms/list`, params);
      
      const terms: any = {};

      poResponse.data.result.terms.forEach(function (term: any) {
        terms[term.term] = term.translation.content;
      });

      response = {
        headers: {
          "Access-Control-Allow-Methods": "*",
          "Access-Control-Allow-Origin": "*",
          "Access-Control-Allow-Headers": "*",
        },
        statusCode: 200,
        body: JSON.stringify(terms),
      };

      return response;
    } else {
      response = {
        headers: {
          "Access-Control-Allow-Methods": "*",
          "Access-Control-Allow-Origin": "*",
          "Access-Control-Allow-Headers": "*",
        },
        statusCode: 200,
        body: "",
      };

      return response;
    }
  } catch (err: any) {
    console.error(err);
    return {
      headers: {
        "Access-Control-Allow-Methods": "*",
        "Access-Control-Allow-Origin": "*",
        "Access-Control-Allow-Headers": "*",
      },
      statusCode: 400,
      body: err.message,
    };
  }
};