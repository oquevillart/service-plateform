import './styles/url-shortener.css';

window.addEventListener("load", () => {
    let form = document.getElementById("url-shortener-form");

    let areaWrapper = document.querySelector('.area-wrapper');
    let urlArea = document.querySelector('.generated-url-area');

    let btnProcess = document.querySelector(".btn-process");

    async function writeClipboardText(text) {
        try {
            await navigator.clipboard.writeText(text);
        } catch (error) {
            console.error(error.message);
        }
    }

    function fetchUrl() {
        const data = new FormData(form);
        const controller = new AbortController()

        fetch("/ajax/url-shortener", {
            method: "POST",
            body: data,
        }).then(response => response.json())
            .then(data => {
                areaWrapper.classList.add("active");
                if (data.error != undefined) {
                    urlArea.classList.add("error");
                    urlArea.innerHTML = data.error
                }
                else{
                    urlArea.innerHTML = data.url
                }
                
            })
            .catch(err => {
                areaWrapper.classList.add("error");
                urlArea.innerHTML = "Error occured"
            })
    }


    form.addEventListener("submit", (e) => {
        e.preventDefault();
        btnProcess.disabled = true;
        
        fetchUrl()
        setTimeout(() => {
            btnProcess.disabled = false
            urlArea.classList.remove('error')
        },5000)
    })

    urlArea.addEventListener("click", () => {
        writeClipboardText(urlArea.innerHTML)
    })
});
