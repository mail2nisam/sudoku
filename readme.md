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

## Menus
- **New** - For generating a parially filled sudo
- **Validate Solution** - Check the filled solution is a valid one or not
- **Solve** - Will disaply solution of the puzzle if you give up
## Some techcnical notes
### Routes
- `sudoku/uuid` this is the puzlle route you will get laoded
- **Check possiblity of a number in a given cell [POST]**
   - URL: `/api/sudoku/check-possibility/{number?}`
   - Method: POST
   - Description : This API route will check whether a number can be occupied in a gie cell or not
   - Request Type:  (application/json)
      ``` 
        /api/sudoku/check-possibility/5
        {
            row : 0
            column : 5
            matrix_id: e416fb00-1d30-11e9-a13f-dd982b9fcaa6
        }
     ```
    - Response
        ```
        {
            status : true
        }
         ```
    
    
        
- **Validate the solution**
    - URL : `/api/sudoku/validate/{puzzleId}` 
    - Method : POST
    - Description : This POST API route will check the current puzzle is valid or not (using sudoku rules)
    - Request Type : (application/json)
        ```
        /api/sudoku/validate/e416fb00-1d30-11e9-a13f-dd982b9fcaa6
        {
            "matrix":
                [
                    ["8","7","5","1","6","9","3","4","2"],
                    ["4","3","9","7","5","2","1","6","8"],
                    ["1","2","6","3","4","8","7","5","9"],
                    ["3","1","8","6","9","7","5","2","4"],
                    ["5","9","4","2","3","1","8","7","6"],
                    ["7","6","2","4","8","5","9","3","1"],
                    ["2","4","7","8","1","3","6","9","5"],
                    ["6","5","1","9","7","4","2","8","3"],
                    ["9","8","3","5","2","6","4","1","7"]
                ]
        }
        ```
    - Response
        ```
        {
            status : true
        }
         ```
    
    
   - **Auto resolve the puzzle**
        - URL : `/api/sudoku/auto-resolve/{puzzleId}` 
        - Method :GET
        - Description: This api route is used to auto resolve for the current puzzle
        - Request Type : (application/json)
            ```
            /api/sudoku/validate/e416fb00-1d30-11e9-a13f-dd982b9fcaa6
            ```
        - Response
        ```
                 [
                    ["8","7","5","1","6","9","3","4","2"],
                    ["4","3","9","7","5","2","1","6","8"],
                    ["1","2","6","3","4","8","7","5","9"],
                    ["3","1","8","6","9","7","5","2","4"],
                    ["5","9","4","2","3","1","8","7","6"],
                    ["7","6","2","4","8","5","9","3","1"],
                    ["2","4","7","8","1","3","6","9","5"],
                    ["6","5","1","9","7","4","2","8","3"],
                    ["9","8","3","5","2","6","4","1","7"]
                ]
        ```

