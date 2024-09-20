# Database Notes

## Response

1. Potential Problem

    The responses could be structured better in the database as there is no linking between the response and the choices made when the options are already defined (single and multiple choices questions) also there are questions where the response has multiple parts like the Temporal (from - to) so the content of the response being a text field is not sufficient to store the necessary data

1. Possible Solutions

    - we could change the field type to JSON so we can store all the data we need then use the question type to detetmine the available keys however with this solution the link between the response and the choice will not be visible in the database schema

    - we could also turn the content of the response to an entity and extend that entity for the different types and if needed each response could have many contents (the name could be changed to better fit the new purpose)

## City

1. Potential Problem

    The city as well as the district, the province and the country entities could easily contain a few duplicates as there are no unique constraints to prevent them

1. Possible Solutions

    - we could add a unique constraint for the country name and also in the pairs in the other three entities to prevent such inconsistencies
