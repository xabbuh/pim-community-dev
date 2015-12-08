@javascript
Feature: Associate a product
  In order to create associations between products and groups
  As a product manager
  I need to associate a product to other products and groups

  Background:
    Given a "footwear" catalog configuration
    And the following products:
      | sku            |
      | charcoal-boots |
      | black-boots    |
      | gray-boots     |
      | brown-boots    |
      | green-boots    |
      | shoelaces      |
      | glossy-boots   |
    And I am logged in as "Julia"

  Scenario: Sort associated products
    Given I edit the "charcoal-boots" product
    When I visit the "Associations" tab
    And I select the "Cross sell" association
    And I check the row "shoelaces"
    And I check the row "black-boots"
    And I save the product
    Then the row "shoelaces" should be checked
    And the row "black-boots" should be checked
    And I should be able to sort the rows by Is associated
    And I should be able to sort the rows by SKU

  @jira https://akeneo.atlassian.net/browse/PIM-5161
  Scenario: Grid is sorted by default by "is associated"
    Given the following products:
      | sku          |
      | red-boots    |
      | purple-boots |
      | yellow-boots |
      | orange-boots |
      | white-boots  |
    And the following associations for the product "red-boots":
      | type   | product     |
      | X_SELL | black-boots |
      | X_SELL | gray-boots  |
    And I edit the "red-boots" product
    When I visit the "Associations" tab
    Then I should see the text "black-boots"
    And I should see the text "gray-boots"
    And the rows "black-boots and gray-boots" should be checked
    And the rows should be sorted descending by Is associated

  @jira https://akeneo.atlassian.net/browse/PIM-5161
  Scenario: Grid is sortable by "is associated"
    Given the following products:
      | sku          |
      | red-boots    |
      | purple-boots |
      | yellow-boots |
      | orange-boots |
      | white-boots  |
    And the following associations for the product "red-boots":
      | type   | product     |
      | X_SELL | black-boots |
      | X_SELL | gray-boots  |
    And I edit the "red-boots" product
    When I visit the "Associations" tab
    Then I should be able to sort the rows by Is associated
