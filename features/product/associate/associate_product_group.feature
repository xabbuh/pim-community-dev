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

  @skip @jira https://akeneo.atlassian.net/browse/PIM-4788
  Scenario: Associate a product to another group
    Given I edit the "charcoal-boots" product
    When I visit the "Associations" tab
    And I select the "Upsell" association
    And I press the "Show groups" button
    And I check the row "caterpillar_boots"
    And I save the product
    And I select the "Upsell" association
    And I press the "Show groups" button
    Then I should see the text "0 products and 1 groups"
    Then the row "caterpillar_boots" should be checked

  @skip @jira https://akeneo.atlassian.net/browse/PIM-4788
  Scenario: Associate a product to multiple products and groups
    Given I edit the "black-boots" product
    When I visit the "Associations" tab
    And I select the "Substitution" association
    And I check the row "charcoal-boots"
    And I select the "Upsell" association
    And I check the row "glossy-boots"
    And I press the "Show groups" button
    And I check the row "caterpillar_boots"
    And I select the "Cross sell" association
    And I check the row "similar_boots"
    And I press the "Show products" button
    And I check the rows "shoelaces, gray-boots, brown-boots and green-boots"
    And I save the product
    And I select the "Cross sell" association
    Then I should see the text "4 products and 1 groups"
    And I select the "Upsell" association
    Then I should see the text "1 products and 1 groups"
    And I select the "Substitution" association
    Then I should see the text "1 products and 0 groups"
    And I select the "Pack" association
    Then I should see the text "0 products and 0 groups"
