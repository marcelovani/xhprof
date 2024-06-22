@javascript @gui
Feature: List
  In order see analyze traces
  as a user
  I want to see reports in the UI

  Background:
    Given I am on "/"

  @list
  Scenario: Check the list page
    Then I should see an "#box-table-a > tbody > tr:nth-child(1) > td:nth-child(1) > a" element
    And I should see "Last" in the ".options-list" element
    And I should see "Today" in the ".options-list" element

  @functions
  Scenario: Check the filter by function
    When I click the "#box-table-a tr:first-child > td:first-child > a" element
    Then the response should contain "Min Wall Time"
    Then the response should contain "Display run Incl. Wall Time (microsec)"
    Then the response should contain "microsecond"
    Then I should see a "#container .highcharts-container" element
    Then I wait "1" second
    When I click the ".tableFloatingHeaderOriginal tr:nth-child(1) th:nth-child(1)" element
    Then I should see a "#box-table-a tbody > tr > td" element
    Then I wait "1" second
    When I click the ".tableFloatingHeaderOriginal tr:nth-child(2) th:nth-child(1)" element
    Then I should see a "#box-table-a tbody > tr > td" element
    Then I wait "1" second
    When I click the ".tableFloatingHeaderOriginal tr:nth-child(3) th:nth-child(1)" element
    Then I should see a "#box-table-a tbody > tr > td" element
    When I follow "main()"
    Then the query string "func" should match "main"
    When I click the "a.xh-title" element
    And I should see "Last" in the ".options-list" element

  @callgraph @db
  Scenario: Check the callgraph
    When I click the "#box-table-a tr:first-child > td:first-child > a" element
    Then I click the ".callgraph.form-button" element
    Then I should see a ".container .dash-header #options form #format" element
    # @todo click on this
    And I should see a ".container .dash-header #show_internal" element
    # @todo click on this
    And I should see a ".container .dash-header .threshold" element

  @apis @db
  Scenario: Check the Db API
    Given I am on "/api/db/?run=5824ff778a7c8&links=1&show_internal=0"
    Then the query string "show_internal" should match "0"
    And the query string "links" should match "1"
    And the response should contain "digraph call_graph {"
    And the response should contain "graph [label="
    And the response should contain "style=\"filled"
    And the response should contain "fontstyle=\"bold"
    And the response should contain "fontname=\"Arial"
    And the response should contain "node [shape=\"box"
    Given I am on "/api/db/?run=5824ff778a7c8&links=1&show_internal=0&func=main"
    And the query string "func" should match "main"

  @apis @file
  Scenario: Check the File API
    Given I am on "/api/file/?run=1234&links=1&show_internal=0"
    Then the query string "show_internal" should match "0"
    And the query string "links" should match "1"
    And the response should contain "digraph call_graph {"
    And the response should contain "func=drupal_static"

  @history
  Scenario: Check the history list
    When I follow "examples/sample.php"
    Then the response should contain "Data for XH Gui"
    Then I should see a ".highcharts-container" element
    When I click the "#box-table-a tr:first-child > td:first-child > a" element
    Then I should see a ".callgraph.form-button" element

