
const myBtn = document.getElementById('submit');

console.log(myBtn);

let msg ="";

myBtn.addEventListener('click',()=>{


    let errorData = document.getElementById('alert');

    var fname = document.getElementById('fname').value;
    var lname = document.getElementById('lname').value;
    var username = document.getElementById('user').value;
    var password = document.getElementById('pwd').value;
    var dob = document.getElementById('dob').value;
    var email = document.getElementById('email').value;
    var gen = document.getElementsByName('gender');
    var city = document.getElementById('city').value;
    var pic = document.getElementById("upload").files[0];
    var checkP = document.getElementById('checkPointer');

     
    
console.log(city);

    var flag = 0;

    for(i=0;i<gen.length;i++)
    {
        if(gen[i].checked == true)
        {
            flag =1;
            break
        }
        
    }
    let  displayMsg =      document.getElementById('alert');

    // console.log(flag);
    
    if(fname != "" && lname != "" && username != "" && password != "" && dob != "" && email != ""&& flag == 1  && city == 1)
    {
       

       displayMsg.classList.remove('alert-danger')
       displayMsg.classList.add('alert-success')

       errorData.classList.remove('d-none');
       errorData.classList.add('d-flex');

       msg=`Thank you ${fname} for login us!....`;
    }
    else{

         displayMsg.classList.remove('alert-success')
        displayMsg.classList.add('alert-danger')
      

         errorData.classList.remove('d-none');
        
        errorData.classList.add('d-flex');

         document.getElementById('error').innerHTML = msg;

        msg =""
        if(fname == "" && lname == "" && username == "" && password == "" && dob == "" && email == ""&& flag == 0  && city == 0)
        {
            msg = "Empty form Submitted....";
        }
        else{

            if(fname == "")
            {
                msg = "First Name is required...";
            }
            else if(lname == "")
            {
                msg = "Last Name is required...";
            }
            else if(username == "")
            {
                msg = "User Name is required...";
            }
            else if(password == "")
            {
                msg = "Password is required...";
            }
            else if(password.length < 6)
            {
                msg = "Password length must be minimum 6 character long ....";
            }
            else if(dob == "")
            {
                msg = "DOB required ..."
            }
            else if(email == "")
            {
                msg = "Email id required"
            }
            else if(flag == 0)
            {
                msg ="Geneder selection required..."
            }
            else if(city== 0)
            {
                msg="City name required";
            }
            else if(pic == '')
            {
                msg ="Please upload your picture...."
            }
            else if(checkP.checked == false)
            {
                msg="Please check in I Agree option ...";
            }

            
        }
        

       
        
       

        
    }
     document.getElementById('error').innerHTML = msg;

})

 


/*
multi line commend

var x = 10;

console.log("x = "+x);
console.log("type of x = "+ typeof(x));

x = "Srija";

console.log("x = "+x);
console.log("type of x = "+ typeof(x));
x = 22/7;

console.log("x = "+x);
console.log("type of x = "+ typeof(x));

x = true
console.log("x = "+x);
console.log("type of x = "+ typeof(x));

x = null

console.log("x = "+x);
console.log("type of x = "+ typeof(x));

x = undefined
console.log("x = "+x);
console.log("type of x = "+ typeof(x));


let y = 20 

console.log('y = '+y);
console.log('type of y = '+ typeof(y));

 y = "Debajit" 

console.log('y = '+y);
console.log('type of y = '+ typeof(y));


const PI = 22/7;

console.log('PI = '+PI);

var x =  parseInt(prompt("Enter x :: ",15)) || 15 ;
var  y = parseInt(prompt("Enter y :: ",35)) || 35;

console.log("x = "+x);
console.log("y = "+y);
console.log('Sum of '+x+' and '+y+' = '+ (x+y));

console.log(` Sum of ${x} and ${y} = ${(x+y)}`)

 */
