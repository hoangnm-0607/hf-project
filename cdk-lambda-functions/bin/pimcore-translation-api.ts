#!/usr/bin/env node
import 'source-map-support/register';
import * as cdk from 'aws-cdk-lib';
import { PimcoreTranslationApiStack } from '../lib/pimcore-translation-api-stack';

const app = new cdk.App();

/**
 * Creates the translation api stack
 * 
 * Local deployment command: DEPLOYMENT_ENV=DEV npx cdk deploy -a 'npx ts-node --prefer-ts-exts bin/translation-api.ts' --profile=hansefit-dev
 */
new PimcoreTranslationApiStack(app, "pimcore-translation-api-stack", {
  env: { account: process.env.CDK_DEFAULT_ACCOUNT, region: process.env.CDK_DEFAULT_REGION }
});