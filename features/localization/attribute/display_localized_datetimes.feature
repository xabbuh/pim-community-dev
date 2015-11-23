@javascript
Feature: Display localized datetimes in the attribute show
  In order to have complete localized UI
  As a product owner
  I need to be able to show localized datetimes

  Background:
    Given an "default" catalog configuration

  Scenario: I can see english format datetimes in the attribute show
    Given I am logged in as "Julia"
    When I edit the "sku" attribute
    Then I should see a english datetime in the Attribute creation
    And I should see a english datetime in the Attribute update

  Scenario: I can see french format datetimes in the attribute show
    Given I am logged in as "Julien"
    When I edit the "sku" attribute
    Then I should see a english datetime in the Attribute creation
    And I should see a english datetime in the Attribute update
