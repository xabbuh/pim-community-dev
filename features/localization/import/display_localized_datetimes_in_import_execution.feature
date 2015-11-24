@javascript
Feature: Display localized datetimes in the import execution show
  In order to have complete localized UI
  As a product owner
  I need to be able to show localized datetimes

  Background:
    Given the "footwear" catalog configuration
    And the following CSV file to import:
      """
      sku
      sandals
      """
    And the following job "footwear_product_import" configuration:
      | filePath   | %file to import% |

  Scenario: I can see english format datetimes in execution show
    Given I am logged in as "Julia"
    And I am on the "footwear_product_import" import job page
    When I launch the import job
    And I wait for the "footwear_product_import" job to finish
    Then I should see english datetimes in the column Start
    And I should see english datetimes in the column End

  Scenario: I can see french format datetimes in execution show
    Given I am logged in as "Julien"
    And I am on the "footwear_product_import" import job page
    When I launch the import job
    And I wait for the "footwear_product_import" job to finish
    Then I should see french datetimes in the column Début
    And I should see french datetimes in the column Fin

  Scenario: I can see english format datetimes in executions index
    Given I am logged in as "Julia"
    And I am on the "footwear_product_import" import job page
    And I launch the import job
    And I wait for the "footwear_product_import" job to finish
    When I am on the importExecution index page
    Then I should see english datetimes in the column Date

  Scenario: I can see french format datetimes in executions index
    Given I am logged in as "Julien"
    And I am on the "footwear_product_import" import job page
    And I launch the import job
    And I wait for the "footwear_product_import" job to finish
    When I am on the importExecution index page
    Then I should see french datetimes in the column Date

  Scenario: I can see english format datetimes in job index
    Given I am logged in as "Julia"
    And I am on the "footwear_product_import" import job page
    And I launch the import job
    And I wait for the "footwear_product_import" job to finish
    When I am on the jobTracker index page
    Then I should see english datetimes in the column Started at

  Scenario: I can see french format datetimes in job index
    Given I am logged in as "Julien"
    And I am on the "footwear_product_import" import job page
    And I launch the import job
    And I wait for the "footwear_product_import" job to finish
    When I am on the jobTracker index page
    Then I should see french datetimes in the column Started at
