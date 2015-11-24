@javascript
Feature: Display localized datetimes in the export execution show
  In order to have complete localized UI
  As a product owner
  I need to be able to show localized datetimes

  Background:
    Given the "apparel" catalog configuration
    And I launched the completeness calculator

  Scenario: I can see english format datetimes in export executions show
    Given I am logged in as "Julia"
    And I am on the "ecommerce_product_export" export job page
    When I launch the export job
    And I wait for the "ecommerce_product_export" job to finish
    Then I should see english datetimes in the column Start
    And I should see english datetimes in the column End

  Scenario: I can see french format datetimes in export executions show
    Given I am logged in as "Julien"
    And I am on the "ecommerce_product_export" export job page
    When I launch the export job
    And I wait for the "ecommerce_product_export" job to finish
    Then I should see french datetimes in the column Début
    And I should see french datetimes in the column Fin

  Scenario: I can see english format datetimes in export executions index
    Given I am logged in as "Julia"
    And I am on the "ecommerce_product_export" export job page
    And I launch the export job
    And I wait for the "ecommerce_product_export" job to finish
    When I am on the exportExecution index page
    Then I should see english datetimes in the column Date

  Scenario: I can see french format datetimes in export executions index
    Given I am logged in as "Julien"
    And I am on the "ecommerce_product_export" export job page
    And I launch the export job
    And I wait for the "ecommerce_product_export" job to finish
    When I am on the exportExecution index page
    Then I should see french datetimes in the column Date

  Scenario: I can see english format datetimes in job index
    Given I am logged in as "Julia"
    And I am on the "ecommerce_product_export" export job page
    And I launch the export job
    And I wait for the "ecommerce_product_export" job to finish
    When I am on the jobTracker index page
    Then I should see english datetimes in the column Started at

  Scenario: I can see french format datetimes in job index
    Given I am logged in as "Julien"
    And I am on the "ecommerce_product_export" export job page
    And I launch the export job
    And I wait for the "ecommerce_product_export" job to finish
    When I am on the jobTracker index page
    Then I should see french datetimes in the column Started at
