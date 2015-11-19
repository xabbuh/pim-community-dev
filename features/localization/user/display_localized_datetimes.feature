@javascript
Feature: Display localized datetimes in the user show
  In order to have complete localized UI
  As a product owner
  I need to be able to show localized datetimes

  Background:
    Given an "default" catalog configuration

  Scenario: I can see english format datetimes in my profile
    Given I am logged in as "Julia"
    When I am on my profile page
    Then I should see a english datetime in the User creation
    And I should see a english datetime in the User update
    And I should see a english datetime in the User last login

  Scenario: I can see french format datetimes in my profile
    Given I am logged in as "Julien"
    When I am on my profile page
    Then I should see a french datetime in the User creation
    And I should see a french datetime in the User update
    And I should see a french datetime in the User last login

  Scenario: I can see english format datetimes in Julien's profile
    Given I am logged in as "Julia"
    When I show the "Julien" user
    Then I should see a english datetime in the User creation
    And I should see a english datetime in the User update
    And I should see a english datetime in the User last login

  Scenario: I can see french format datetimes in Julia's profile
    Given I am logged in as "Julien"
    When I show the "Julia" user
    Then I should see a french datetime in the User creation
    And I should see a french datetime in the User update
    And I should see a french datetime in the User last login
