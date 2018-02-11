# Section/question/answer seeder

Files **questionnaire_texts_it.json** and **questionnaire_metadata.json** contain all section/question/answer information.
They share the same hierarchical structure: list of sections containing a list of questions containing a list of answers.

**questionnaire_texts_it.json** contains section/question/answer localized texts.

**questionnaire_metadata.json** contains other section/question/answer information, only question-related
and used during validation so far:

- **type**: single_choice, multiple_choice, text
- **required**
- **options**: a list of custom attributes:
  - **makes_next_required**: whether the question makes the following questions mandatory
    - **next**: how many questions after the current are made mandatory
    - **answer**: which answer of the current question makes the next mandatory
