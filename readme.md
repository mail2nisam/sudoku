# Simple Sudoku 

This repository having a simple sudoku game using Laravel, MySql and Javascript.


## Setup
- Clone the repository to your local machine
- Install vendor packages `composer install`
- Install node modules `npm i`
- Create database and update the credentials in .env file
- Run the database migration `php artisan migrate`
- Compile the assets `npm run dev`
- Start the serve `php artisan serve`
## How to work
- When you get started the aplication a partially filled sudoku will be loaded for you.
- Fill the empty cells until it resolves fully
- If you give up the puzzle you can just click on `resolve` button on top
- If you are filled all the cell at your own risk, you can just verify the probelem is resoved correctly or not

## Some techcnical note
### Routes
- `sudoku/uuid` this is the puzlle route you will get laoded
- `/api/sudoku/check-possibility/{number?}` this API route will check whether a number can be occupied in a gie cell or not
- `/api/sudoku/validate/{puzzleId}` This POST API route will check the current puzzle is valid or not (using sudoku rules)
- `/api/sudoku/auto-resolve/{puzzleId}` This api route is used to auto resolve for the current puzzle

