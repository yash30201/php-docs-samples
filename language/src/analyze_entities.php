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

# [START language_entities_text]
use Google\Cloud\Language\V1\AnalyzeEntitiesRequest;
use Google\Cloud\Language\V1\Client\LanguageServiceClient;
use Google\Cloud\Language\V1\Document;
use Google\Cloud\Language\V1\Document\Type;
use Google\Cloud\Language\V1\Entity\Type as EntityType;

/**
 * @param string $text The text to analyze
 */
function analyze_entities(string $text): void
{
    // Create the Natural Language client
    $languageServiceClient = new LanguageServiceClient();

    // Create a new Document, add text as content and set type to PLAIN_TEXT
    $document = (new Document())
        ->setContent($text)
        ->setType(Type::PLAIN_TEXT);

    // Call the analyzeEntities function
    $request = (new AnalyzeEntitiesRequest())
        ->setDocument($document);
    $response = $languageServiceClient->analyzeEntities($request);
    $entities = $response->getEntities();
    // Print out information about each entity
    foreach ($entities as $entity) {
        printf('Name: %s' . PHP_EOL, $entity->getName());
        printf('Type: %s' . PHP_EOL, EntityType::name($entity->getType()));
        printf('Salience: %s' . PHP_EOL, $entity->getSalience());
        if ($entity->getMetadata()->offsetExists('wikipedia_url')) {
            printf('Wikipedia URL: %s' . PHP_EOL, $entity->getMetadata()->offsetGet('wikipedia_url'));
        }
        if ($entity->getMetadata()->offsetExists('mid')) {
            printf('Knowledge Graph MID: %s' . PHP_EOL, $entity->getMetadata()->offsetGet('mid'));
        }
        printf(PHP_EOL);
    }
}
# [END language_entities_text]

// The following 2 lines are only needed to run the samples
require_once __DIR__ . '/../../testing/sample_helpers.php';
\Google\Cloud\Samples\execute_sample(__FILE__, __NAMESPACE__, $argv);
