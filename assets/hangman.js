import './styles/hangman.css';

window.addEventListener("load", () => {
    let form = document.getElementById("word-form");

    let wordInput = document.querySelector('.text-input');
    let areaWrapper = document.querySelector('.area-wrapper');
    let urlArea = document.querySelector('.generated-word-area');

    let btnProcess = document.querySelector(".btn-process");

    let word = "";


    function useRegex(input) {
        let regex = /[ '-]/g;
        return regex.test(input);
    }

    String.prototype.replaceAt = function(index, replacement) {
        return this.substring(0, index) + replacement + this.substring(index + replacement.length);
    }

    fetch("/ajax/hangman/generate", {
        method: "GET",
    }).then(response => response.json())
        .then(data => {
            if (data.error != undefined) {
                urlArea.classList.add("error");
                urlArea.innerHTML = data.error
            }
            else {
                word = data.word;
                let secretWord = "";
                for (let i = 0; i < data.word.length; i++) {
                    const element = data.word[i];
                    if (useRegex(element) == false) {
                        secretWord += "_";
                    }
                    else{
                        secretWord += element
                    }
                }
                urlArea.innerHTML = secretWord
            }

        })
        .catch(err => {
            areaWrapper.classList.add("error");
            urlArea.innerHTML = "Error occured"
        })


    form.addEventListener("submit", (e) => {
        e.preventDefault();
        let isTrue = false;
        let data = new FormData(form)
        for (let i = 0; i < word.length; i++) {
            if (word[i] == data.get('word')) {
                if (urlArea.innerHTML[i] == "_") {
                    urlArea.innerHTML = urlArea.innerHTML.replaceAt(i,data.get('word'));
                    wordInput.value = "";
                    isTrue = true;
                }
            }
        }
        if (isTrue == false) {
            areaWrapper.classList.add("error");
        }
        else{
            areaWrapper.classList.add("active");
        }
    })

});


