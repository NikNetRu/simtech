/*
 * ��������� ��� Email
 * ���������� �������
 */
    let corretFormat = [];
    let filename = document.getElementById("formFile");
    filename.addEventListener('change',(e) => {
        let f = e.target.files,
            len = f.length;
        for (let i=0;i<len;i++){
            console.log(f[i].name);
            if (f[i].name.match(/[a-zA-Z�-��-�0-9]*.jpg|.bmp|.jpeg|.gif/) !== null) {
                filename.classList.remove('invalidFile');
                filename.classList.add('validFile');}
            else {
                filename.classList.remove('validFile');
                filename.classList.add('invalidFile');
                filename.valid;
                alert("������������ ������ ������, ��� �� ����� ���������, ��������� � ������� jpg, bmp, jpeg, gif");
            }
        }
    });


