const burger = document.querySelector("#burger");
const links = document.querySelector("#links");
burger.addEventListener("click",() => {

        if(burger.classList.contains('is-opened')){
            burger.classList.add('is-closed');
            burger.classList.remove('is-opened');
            links.style.display = 'none';
        }else{
            burger.classList.remove('is-closed');
            burger.classList.add('is-opened');
            links.style.display = "flex";
        }
    }
)

