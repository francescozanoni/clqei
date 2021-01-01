# Section/question/answer seeder

Files **questionnaire_texts_it.json** and **questionnaire_metadata.json** contain all section/question/answer information.
They share the same hierarchical structure: list of sections containing a list of questions containing a list of answers.

 * **questionnaire_texts_it.json** contains section/question/answer localized texts.

 * **questionnaire_metadata.json** contains other section/question/answer information, only question-related
and used during validation so far:

    - **type**: single_choice, multiple_choice, text
    - **required**
    - **options**: a list of custom attributes:
      - **makes_next_required**: whether the question makes the following questions mandatory
        - **next**: how many questions after the current are made mandatory
        - **answer**: which answer of the current question makes the next mandatory

### Examples of manual section/question/answer management

 * section addition:
    ```sql
    INSERT INTO sections (position,
                          created_at,
                          title,
                          header,
                          footer)
    VALUES (8,
            '2021-01-01 13:48:09',
            'Note',
            NULL,
            NULL);
    ```
* question addition:
    ```sql
    INSERT INTO questions (text,
                           section_id,
                           type,
                           required,
                           options,
                           position,
                           created_at)
    VALUES ('Note personali',
            8,
            'text',
            0,
            NULL,
            1,
            '2021-01-01 13:48:10');
    ```