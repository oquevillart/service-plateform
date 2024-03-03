import './styles/hangman.css';

window.addEventListener("load", () => {
    let form = document.getElementById("word-form");

    let wordInput = document.querySelector('.text-input');
    let areaWrapper = document.querySelector('.area-wrapper');
    let urlArea = document.querySelector('.generated-word-area');

    let btnProcess = document.querySelector(".btn-process");

    let word = "";
    let oldWord = [];
    let life = 5;

    let lifebar = document.querySelector(".fill");

    function useRegex(input) {
        let regex = /[ '-]/g;
        return regex.test(input);
    }

    String.prototype.replaceAt = function (index, replacement) {
        return this.substring(0, index) + replacement + this.substring(index + replacement.length);
    }

    function fetchWord() {
        fetch("/ajax/hangman/generate", {
            method: "GET",
        })
            .then(response => response.json())
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
                        else {
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
    }

    function fetchNewWord() {
        let data = JSON.stringify(oldWord);
        fetch("/ajax/hangman/generate/new", {
            method: "POST",
            body: data,
            headers: {        
                "Content-Type": "application/json"    
            } 
        })
            .then(response => response.json())
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
                        else {
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
    }

    fetchWord();

    form.addEventListener("submit", (e) => {
        e.preventDefault();
        areaWrapper.classList.remove("error", "active");
        console.log(oldWord);
        let isTrue = false;
        let data = new FormData(form)
        for (let i = 0; i < word.length; i++) {
            if (word[i] == data.get('word')) {
                if (urlArea.innerHTML[i] == "_") {
                    urlArea.innerHTML = urlArea.innerHTML.replaceAt(i, data.get('word'));
                    isTrue = true;
                }
            }
        }
        if (urlArea.innerHTML === word) {
            alert("win")
            oldWord.push(word);
            areaWrapper.classList.remove("error", "active");
            lifebar.style.width = "100%";
            life = 5;
            fetchNewWord();
        }
        wordInput.value = "";
        if (isTrue == false) {
            areaWrapper.classList.add("error");
            life = life - 1;

            if (life == 4) {
                lifebar.style.width = "80%";
                lifebar.style.backgroundColor = "green";
            }
            if (life == 3) {
                lifebar.style.width = "60%";
                lifebar.style.backgroundColor = "green";
            }
            if (life == 2) {
                lifebar.style.width = "40%";
                lifebar.style.backgroundColor = "orange";
            }
            if (life == 1) {
                lifebar.style.width = "20%";
                lifebar.style.backgroundColor = "brown";
            }
            if (life < 1) {
                lifebar.style.width = "100%";
                lifebar.style.backgroundColor = "green";
                alert('defeat... the word is : ' + word);
                oldWord.push(word);
                life = 5;
                fetchNewWord();
                areaWrapper.classList.remove("error", "active");
            }
        }
        else {
            areaWrapper.classList.add("active");
        }
    })

});