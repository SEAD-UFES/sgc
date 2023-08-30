# Changelog

The format is (loosely) based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/)

## [Unreleased]
--
## [0.91.0-Beta] - 2023-08-10
### Added or Changed
- Employee: Ticket #286434 - mandatory fields for registering collaborators - have been limited to Name, CPF, and Gender.

## [0.90.3-Dev] - 2023-04-14
### Fixed
- Typo
- Cancel button route on designate view form, hide import button
- Rectify component variable name in designate view

## [0.90.0-Dev] - 2023-02-24
### Fixed
- Remove bootstrap classes with typo
- Course: download course employees' documents zip

## [0.89.0-Dev] - 2023-01-30
### Fixed
- Bond: course class name
- Bond tests: course class
- Course class validation: nullable cpp
- Bond detail view: role name
### Added or Changed
- Bond form: course class drop down context aware
- System menu: change password and logout
- Bond: course class
- Course class creation: use given course
- Course: show
- Course class: service, controller, view, route

## [0.83.2-Dev] - 2022-12-22
### Fixed
- Applicant list and domain event handle null course

## [0.83.1-Dev] - 2022-10-07
### Fixed
- Bond forms: update gates and item
### Added or Changed
- Bond: add announcement
- Searchable selects: focus to search box on click
- Forms: add searchable select

## [0.80.1-Dev] - 2022-09-20
### Fixed
- Bond request: relay bond correctly to gate check
- Models: minor fix
- Approved create blade: remove useless field
- Responsibility Controller: amend typo
- Employee marital status: handle null value
- Employee gender: handle null value
- View: fix parameters problems
- Pole model: add missing parameter
- Responsibility composer: fix wrong file name
- Mend typo
### Added or Changed
- Employee Bond: show creation and update info on show views
- Institutional login: confirmation required and confirmed messages

## [0.78.4-Dev] - 2022-08-25
### Fixed
- Notice emails: format content and layout
- Notice emails: broken layout and escaped characters
- Notice emails: add proper mail container markdown
- Notice emails: handle null employee of sender user
### Added or Changed
- Employee: add institut. details and notices
- Course: add lms url
- User: manual employee link
- Create InstitutionEmployeeLoginCreated mail
- Bank account and qualification: update tests
- Bank account and qualification: make bank account factory
- Bank account and qualification: update views
- Bank account and qualification: update services
- Bank account and qualification: update requests validations
- Bank account and qualification: update bond controller
- Bank account and qualification: update models
- Knowledge area: make enum
- Bank account and qualification: migrations

## [0.65.1-Dev] - 2022-07-28
### Fixed
- Course model: fix typo
- Employee form: standardize and remove duplicate html attributes
### Added or Changed
- UserTypeAssignment: add view
- Approved: create one

## [0.63.2-Dev] - 2022-06-22
### Fixed
- Factories: typo
- Employee show: verify if bond is set
- Role form: handle grant value
- Log: get original models data
- Employee data: remove conflicting pattern
- Employee data: remove conflicting pattern
- Employee document create: format cpf string
- Employee document list: format cpf string
- Employee basic data: format cpf string
- Bond list: format cpf string
- Employee show: format cpf, phones and cep
- Employee list: format cpf and phones
- Approved list: format phone strings
- Role form: grant value conversion
### Added or Changed
- Log: log forbidden access attempt

## [0.62.0-Dev] - 2022-05-31
### Fixed
- Import approveds: form checkbox behavior
- Import approveds: relax form required fields
- Rights: make ldi user see rights documents
- Export documents: adapted to the new tables structure
### Added or Changed
- Role: mask input fields
- Employee: mask form input fields
- Import approved: mask input fields
- Feature(approved: recover form values

## [0.58.4-Dev] - 2022-04-28
### Fixed
- Controllers: changed forbidden http response from 401 to 403
- Single document import: store only one of each type
- Multiple documents import: adapted to the new tables structure
- Employee edit: absent variable on form component
- Store employee: adjusted phones fields
- Approveds import: remove carriage return on Excel multi-line cell text
### Added or Changed
- Designate approved: preserve old values on designate form reload

## [0.57.0-Dev] - 2022-01-28
### Fixed
- Force https on prod
### Added or Changed
- Add functionality to update user password

## [0.56.13-Dev] - 2021-01-11
### Fixed
- Fixed broken documents sorting

## [0.56.12-Dev] - 2021-10-29
### Fixed
- Fixed event name collision
- Broken documents filters
- Fixed some broken queries
- SgcLogger and ApprovedStateObserver
- [Issue #40] Fixed behavior when trying to designate an Approved with the - same registered Employee's CPF
- [Issue #37] Fixed cascading deletion of Bonds
- Fixed GenericGates and BondController

## [0.56.5-Dev] - 2021-09-29
### Fixed
- Fixed create-many-2; Made some Requests - validations
- DocumentService Fix
- Fixed document link. Standardized some routes and methods names.
- Fixed user creation
- Minor grammar fix
- User_show new user show page new generic userFilter basic employee - component access buttons to user_show fix on employee show (order docs) - minor fixes
- Fixed Bond Form Component;
- Fixed Course Form Component;
- Fix BondController->review() add routine that sets an impediment true in - case of no 'termo'.
- Fixed Employee Form Component;
- Fixed missing form data on EmployeeTest
- Fixed sort on the State column
- Fixed ambiguous approved query
- Fixed filter parameter 'name' to 'description'
### Added or Changed
- Merge pull request #39 from LDI-Ufes/feat/user_show
- Merge pull request #38 from LDI-Ufes/feat/employee_show_redesign
- Merge pull request #35 from LDI-Ufes/feat/bond_interface_review
- Merge remote-tracking branch 'origin/feat/bond_interface_review' into feat/- bond_interface_review
- Merge remote-tracking branch 'origin/feat/bond_interface_review' into feat/- bond_interface_review
- Merge pull request #34 from LDI-Ufes/feat/normalize_document_upload
- Merge pull request #33 from LDI-Ufes/feat/access_denied_with_401

## [0.49.2-Dev] - 2021-08-27
### Fixed
- Fixed query on the Bond Controller; Added aliases on User model's local - query scopes
- Fixed gate access permission name
- Fix gate name isCor to is Coord
- Add EmployeeDocumentGates and fixes Add EmployeeDocumentGates. Fix some - gates on DocumentController. Add EmployeeGates (Gates only).
- Gates on the bond Bugfix on controller (change gra to coord) Only global - gates for now. Need to do coord cases. Merging only to apply fixes.
- Fixed System menu dividers
- Fix user_list notifications on the bond
- Bugfix home
- Fix sort on documents The sort custom functions don't work if the method - has "_" in the name. Adjust where to avoid ambiguous names
- Save bug fix on sort working on SessionUser
- Fixed some requests feedbacks and form error feedback text style
- Minor fixes related to notifications
- Bugfix on BondReview; disabled unused routes; swapped links for buttons; - tweaked system feedback messages; Added dismiss button on Home - Notifications.
- Fixed column place on views\employee\index
- Update user filters User model now uses the filter system. Update on Pole - filters. New seed on the user (inactive user) Bugfix on the user active - filter
- Buxfix em EmployeeDocument Como BondDocument e EmployeeDocument - compartilham uma função, foi preciso inserir o Filtrable nos dois modelos. - O plano era fazer isso em um commit só, mas acabei esquecendo.
### Added or Changed
- Merge pull request #32 from LDI-Ufes/feat/bond_coord_gates
- Merge pull request #31 from LDI-Ufes/feat/move_base_gates
- Merge pull request #30 from LDI-Ufes/feat/gates_on_employee
- Merge pull request #29 from LDI-Ufes/feat/basic_gates_on_bond
- Merge pull request #28 from LDI-Ufes/feat/gates_on_BondDocument
- Merge pull request #26 from LDI-Ufes/feat/gates_on_rights
- Merge pull request #24 from LDI-Ufes/feat/remove_usertype_from_user
- Merge pull request #23 from LDI-Ufes/feat/user_type_assignments
- Merge pull request #21 from LDI-Ufes/feat/popover_tooltip_working
- Merge pull request #20 from LDI-Ufes/feat/filters_and_sort_on_rigthsIndex
- Merge pull request #19 from LDI-Ufes/feat/filters_and_sort_on_courseType
- Add sort feature on coursetype
- Add filter feature on coursetype
- Merge pull request #18 from LDI-Ufes/feat/filters_bootstrap
- Merge pull request #17 from LDI-Ufes/feat/filters_on_colaboradores
- Merge pull request #16 from LDI-Ufes/feat/filters_on_sistema_models
- Merge pull request #13 from LDI-Ufes/feat/eloquent_filters_prototype
- Merge pull request #12 from LDI-Ufes/feat/sortable_on_bond_documents

## [0.31.0-Dev] - 2021-07-30
### Fixed
- Fix sort on a document The sort custom functions don't work if the method - has "_" in the name. Adjust where to avoid ambiguous names
- UserType Factory and other minor fixes
- Fixed Login test
- Fixed User Factory, changes to the Login Test
### Added or Changed
- Merge pull request #11 from LDI-Ufes/feat/sortable_on_bond
- Merge pull request #10 from LDI-Ufes/feat/sortable_on_employee_document
- Merge pull request #9 from LDI-Ufes/feat/sortable_on_approved
- Merge pull request #8 from LDI-Ufes/feat/sortable_on_approved
- Merge pull request #7 from LDI-Ufes/feat/sortable_on_role
- Merge pull request #6 from LDI-Ufes/feat/sortable_on_course
- Merge pull request #4 from LDI-Ufes/feat/sortable_polos
- Merge pull request #3 from LDI-Ufes/feat/sortable_on_users
- Employee documents mass import
- Simplified Employee registration. Employee software delete cascade logic.
- Employee general flow and UI tweaks
- Detached Employee Documents and Bond Documents. Made Routes; Relationships; - logic (list and create) and Views.
- Bond Model, Seeder, Routes, Controller, Validations and Views. Class - SessionUser. Reworked CourseSeeder. Small interface tweaks. Role - selectionbox.
- Bond Model, Factory, Migration, Seeder,Routes, partial Controller and - partial Views
- DB varchar lengths, some tweaks on interface and more data to RoleSeeder
- Pole Model, Factory, Migration, Seeder, Controller, Validation, Views and - Routes
- Employee show details
- Employee and Role Model. Gender, ID Type, Marital Status, State and User - Type Enum-likes. Web interface reorganization. Other minor improvements.
- System logging
- Validation of User registration or edit with duplicate email
- User delete functionality
- User edit validation
- User edit functionality
- Create User Form Validation
- Update style.css
- User and Employee tweaks
- User, Employee, Course and Role Logic
- Update parcialFooter.blade.php
- User, Employee, Course and Role Model, Factory, Migration, Seeder, - Controller, Views and Routes. Funding, Report  and System Controllers and - Views
- Show Version and Build and Test DB
- Login functionality
