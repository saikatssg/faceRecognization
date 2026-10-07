const btn = document.getElementById("upload");

btn.addEventListener('change',()=>{

    let file = btn.files[0];
    // console.log(file);
    // console.log(file.size);
    // console.log(file.name);
    // console.log();

    let file_name = file.name;

    let ext = file_name.split(".").pop().toUpperCase();

    console.log(ext);
    
    if( ext == "JPG" || ext == "JPEG" || ext =="PNG")
    {
        document.getElementById("errFile").style.display= "none";
        document.getElementById("errFile").innerHTML ="";

        let render = new FileReader();

        render.onload =function(e)
        {
            document.prev.src= e.target.result;
        }

        render.readAsDataURL(file);
    }
    else
    {
        
       
    
        
             document.prev.src = "../images/noprev.png";

         document.getElementById("errFile").style.display="block";
         document.getElementById("errFile").innerHTML= `Given ${file_name} is not a image file, upload Agian...`;

    }
})