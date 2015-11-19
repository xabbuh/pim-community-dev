@javascript
Feature: Display localized datetimes in the product show
  In order to have complete localized UI
  As a product owner
  I need to be able to show localized datetimes

  Background:
    Given an "default" catalog configuration
    And the following product:
      | sku    |
      | sandal |

  Scenario: I can see english format datetimes
    Given I am logged in as "Julia"
    When I am on the "sandal" product page
    Then I should see a english datetime in the Product creation
    And I should see a english datetime in the Product update

  Scenario: I can see french format datetimes
    Given I am logged in as "Julien"
    When I am on the "sandal" product page
    Then I should see a french datetime in the Product creation
    And I should see a french datetime in the Product update
