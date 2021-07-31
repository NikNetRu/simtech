/*
 * Валидатор навешивает обработчик на изменение состояния input для загруженных
 * файлов. 
 */
    let filename = document.getElementById("formFile");
    filename.addEventListener('change',(e) => {
        let f = e.target.files,
            len = f.length;
        for (let i=0;i<len;i++){
            if (f[i].name.match(/[a-zA-Zà-ÿÀ-ß0-9]*.jpg|.bmp|.jpeg|.gif/) !== null) {
                filename.classList.remove('invalidFile');
                filename.classList.add('validFile');}
            else {
                filename.classList.remove('validFile');
                filename.classList.add('invalidFile');
                filename.valid;
                alert("Некорректный формат изображений, подходящие -  jpg, bmp, jpeg, gif");
            }
        }
    });


