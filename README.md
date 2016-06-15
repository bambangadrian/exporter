# Introduction

# Components
- Database Access: Doctrine DBAL
- Excel Library: 
- Data Mapper and Validator
- Entity Builder
- Data Source and Handlers
- Constraint Element and Field Types
- Exporter and Logger (Observer and Subject)

# Requirements
- Composer
- Doctrine
- PhpExcel
- CodeSniffer (dev)

# How To
- Create the data source first (it will be like: excel, database files).
- Create the entity using entity builder that required the data source as parameter.
- Do mapping for field column name and field type for the constraints.
- Setting up the constraint entity for the source entity.
- Create new entity as target that will be done, this step will be same with the source entity creation.
- Create and run the entity mapper, this components will be mapping the source entity to target entity.
- Create a new exporter instance and then run the doExport method to export and save the target entity data into the source entity.

# Changes
## Dev Version 
Add exporter handler, remove the doMassImport method from data-source, provide the table entity to do insert,
update, and delete data, finishing the basic exporter, improvement on library structure, cleaning code to green status
using complete code inspection mode.

Restructure and rebuild the basic exporter, so it will be mapped and exporting between entity-to-entity, 
create standard data source transition so all data source type can be converted to the standard on array data source type.

Separating the data source handler, fixing the entity builder, fixing the mapper abstraction, optimizing the data 
fetching, some bug-fixed under the entity-entity_builder-data_source library.

Optimizing all the entity exporter and importer, fixing bugs under table and constraint entity builder.
Composing the basic logger for exporter.



## Applied Patterns
- SOLID FOR SURE!!
- Simple Factory Pattern
- Template Method Pattern
- Strategy Pattern
- Builder Pattern
- Decorator Pattern
- Observer Pattern

# Next Releases
## Excel Refactoring:
- Spreadsheet security
- Commenting on Cell






