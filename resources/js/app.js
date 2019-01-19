/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

// window.Vue = require('vue');

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

// Vue.component('example-component', require('./components/ExampleComponent.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

// const app = new Vue({
//     el: '#app'
// });

var Sudoku = {
    init: function () {
        this.isNumberAllowed();
        this.autoResolve();
        this.validatePuzzle();
        this.currentMatrix();
    },
    currentMatrix: function () {
        let matrix = [];
        let innerMatrix = [];
        for (let i = 0; i < 9; i++) {
            innerMatrix = [];
            for (let j = 0; j < 9; j++) {
                let cellName = "input_" + i + j;
                innerMatrix[j] = $("input[name='" + cellName + "']").val();
            }
            matrix[i] = innerMatrix
        }
        return matrix;
    },
    isNumberAllowed: function () {
        $("#sudoku").on("blur", ".input-number", function (a) {
            var input = a.target;
            let number = $(input).val();
            window.axios.post("/api/sudoku/check-possibility/" + number, {
                row: $(input).data("row"),
                column: $(input).data("column"),
                matrix_id: $("#matrix-id").val()
            }).then(response => {
                if (!response.data.status) {
                    $(input).addClass("not-allowed")
                } else {
                    $(input).removeClass("not-allowed")
                }
            });
        })
    },
    autoResolve: function () {
        $("#solve_puzzle").click(function (e) {
            e.preventDefault();
            window.axios.get("/api/sudoku/auto-resolve/" + $("#matrix-id").val()).then(response => {
                let matrix = response.data;
                for (let i = 0; i < matrix.length; i++) {
                    let row = matrix[i];
                    for (let j = 0; j < row.length; j++) {
                        let cellValue = row[j];
                        let cellName = "input_" + i + j;
                        $("input[name='" + cellName + "']").val(cellValue).addClass("new-values");
                    }
                }
            });
        });
    },
    validatePuzzle: function () {
        $("#validate_puzzle").click(function (e) {
            e.preventDefault();
            let currentMatrix = Sudoku.currentMatrix();
            window.axios.post("/api/sudoku/validate/" + $("#matrix-id").val(), {
                matrix: currentMatrix
            }).then(response => {
                if(response.data.status===true){
                    $(".validation-alert").removeClass('invisible').removeClass("alert-danger").addClass("alert-success").html("Sudo is Valid");
                }else{
                    $(".validation-alert").removeClass('invisible').removeClass("alert-success").addClass("alert-danger").html("Sudo is not Valid");
                }
            });
        });
    }
};
Sudoku.init();