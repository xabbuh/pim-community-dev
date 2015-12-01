@javascript
Feature: Sort products per fields
  In order to enrich my catalog
  As a regular user
  I need to be able to manually sort products per fields

  Background:
    Given the "default" catalog configuration
    And the following families:
      | code             |
      | computers        |
      | hi_fi            |
      | washing_machines |
    And the following products:
      | sku        | family           |
      | PC         | computers        |
      | Laptop     | computers        |
      | Amplifier  | hi_fi            |
      | CD changer | hi_fi            |
      | Whirlpool  | washing_machines |
      | Electrolux | washing_machines |
      | Mug        |                  |
    And I am logged in as "Mary"

  Scenario: Successfully sort products by family
    Given I am on the products page
    And the grid should contain 7 elements
    And I should be able to sort the rows by Family

  Scenario: Successfully sort products by status
    Given I am on the products page
    And the grid should contain 7 elements
    And I should be able to sort the rows by Status

  # TODO : calculate completeness
  Scenario: Successfully sort products by complete
    Given I am on the products page
    And the grid should contain 7 elements
    And I should be able to sort the rows by Status
