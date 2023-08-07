<?php
/**
 * Copyright 2017 Google Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

/**
 * For instructions on how to run the full sample:
 *
 * @see https://github.com/GoogleCloudPlatform/php-docs-samples/tree/main/language/README.md
 */

namespace Google\Cloud\Samples\Language;

# [START language_entity_sentiment_text]
use Google\Cloud\Language\V1\AnalyzeEntitySentimentRequest;
use Google\Cloud\Language\V1\Client\LanguageServiceClient;
use Google\Cloud\Language\V1\Document;
use Google\Cloud\Language\V1\Document\Type;
use Google\Cloud\Language\V1\Entity\Type as EntityType;

/**
 * @param string $text The text to analyze
 */
function analyze_entity_sentiment(string $text): void
{
    $languageServiceClient = new LanguageServiceClient();

    // Create a new Document, add text as content and set type to PLAIN_TEXT
    $document = (new Document())
        ->setContent($text)
        ->setType(Type::PLAIN_TEXT);

    // Call the analyzeEntitySentiment function
    $request = (new AnalyzeEntitySentimentRequest())
        ->setDocument($document);
    $response = $languageServiceClient->analyzeEntitySentiment($request);
    $entities = $response->getEntities();
    // Print out information about each entity
    foreach ($entities as $entity) {
        printf('Entity Name: %s' . PHP_EOL, $entity->getName());
        printf('Entity Type: %s' . PHP_EOL, EntityType::name($entity->getType()));
        printf('Entity Salience: %s' . PHP_EOL, $entity->getSalience());
        $sentiment = $entity->getSentiment();
        if ($sentiment) {
            printf('Entity Magnitude: %s' . PHP_EOL, $sentiment->getMagnitude());
            printf('Entity Score: %s' . PHP_EOL, $sentiment->getScore());
        }
        print(PHP_EOL);
    }
}
# [END language_entity_sentiment_text]

// The following 2 lines are only needed to run the samples
require_once __DIR__ . '/../../testing/sample_helpers.php';
\Google\Cloud\Samples\execute_sample(__FILE__, __NAMESPACE__, $argv);
