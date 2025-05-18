# Lexer

A [recursive-descent](https://www.geeksforgeeks.org/recursive-descent-parser/) lexer/tokenizer written in PHP for structured natural language input. Originally built to support [RecipeParser](https://github.com/richvigorito/RecipeParser), this module breaks free-form instructions into a clean sequence of tokens suitable for parsing.

## Features

- Given a grammar, identifies tokens 
- Recursive parsing, terminating at T_TERM
- Built for compiler-style natural language preprocessing

### Example (Math)

Input: ``( 11 * ( 1.5 + (2 ^ 3) ) )``
Tokenized Output:
```json
[
  { "type": "T_LPAR", "value": "(" },
  { "type": "T_NUMBER", "value": "11" },
  { "type": "T_MULT", "value": "*" },
  { "type": "T_LPAR", "value": "(" },
  { "type": "T_NUMBER", "value": "1.5" },
  { "type": "T_ADD", "value": "+" },
  { "type": "T_LPAR", "value": "(" },
  { "type": "T_NUMBER", "value": "2" },
  { "type": "T_EXP", "value": "^" },
  { "type": "T_NUMBER", "value": "3" },
  { "type": "T_RPAR", "value": ")" },
  { "type": "T_RPAR", "value": ")" },
  { "type": "T_RPAR", "value": ")" },
]
```

### Example (Recipies)

Input: ``Add 1/2 teaspoon minced garlic.``
Tokenized Output:
```json
[
  { "type": "ACTION", "value": "Add" },
  { "type": "QUANTITY", "value": "1/2" },
  { "type": "UNIT", "value": "teaspoon" },
  { "type": "MODIFIER", "value": "minced" },
  { "type": "INGREDIENT", "value": "garlic" }
]
```

## Design Notes
Parsing is recursive, using function calls to represent grammar rules (in the spirit of recursive-descent or Pratt parsing).

Input is processed left-to-right, and tokens are assembled using a priority-based matching system.

### Future: C Port
This PHP lexer is being ported to a standalone C library for performance and FFI support. The goal is to enable:
Integration with Python, Go, or other languages via FFI
Reuse across natural-language compilers
Cleaner separation of concerns between lexical analysis and parsing

### Roadmap
- PHP prototype
- Full C rewrite
- Expression tree evaluation in C
- Package as shared FFI-compatible library (.so, .dll, etc.)
- Publish bindings for PHP, Python, Go
