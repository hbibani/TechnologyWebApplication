// Heja Bibani
// Student ID: 16301173
// Class: Tues 4:00 pm


function formValid(form)
{
  var obj;

  var valid= true;

  // We need to skip checking the ones which are not needed
  // then check the ones which are needed
  for( var i = 0 ; i < form.length-1 ; i++ )
  {
    var check = true;

    switch( form[ i ].id )
    {
      case "kinName":
      case "kinMobPhone":
      case "kinDayPhone":
      if( (document.getElementById("nextOfKin").style.display=="none" ) &&(document.getElementById("asAboveYes").checked))
      {
        check=false; //if nothing needs to be checked, don't check [makes code simpler]
      }
      else if( ( document.getElementById(form[i].id).value == "" ) &&
      ( ( document.getElementById("nextOfKin").style.display!="none" ) ) )
      {
          check = false; //if something is not required, and is empty , don't check
      }
      break;

      case "emRel":
      case "kinRel":
      {
        check = false;  //to avoid calling this function we need this here [makes code simpler]
      }
      break;

      case "titleOther":
      if( ( document.getElementById( "otherTitle" ).style.display =="none" ) )
      {
        check = false; //if other is not picked, then don't check it
      }
      break;

      case "perPhone":
      case "wPhone":
      case "dayPhone":
      case "address":
      case "suburb":
      case "postCode":
      case "email":
      case "emName":
      case "emRel":
      case "emMobPhone":
      case "emDayPhone":
      if( ( document.getElementById( form[ i ].id ).value == "" ) )
      {
        check = false; //don't check if not required and empty
      }
      break;

      default:
      check = true;
    }

    if( check == true )
    {
      if( !vInput( form[ i ] ) )
      {
        valid = false;
      }
    }
  }

  //last check [most suitable position do not let pass if all three empty]
  if(document.getElementById("perPhone").value == "" && document.getElementById("wPhone").value == "" && document.getElementById("dayPhone").value=="")
  {
       valid = false; //do not let it pass if they are all empty

   }

  return valid;
}

function vInput(element)
{
  var result = true;
  var displayErr = false;
  var regexp1, regexp2;

  //we check each case
  switch( element.id )
  {
    case "title":
    if( element.value=="" )
    {
      result = false;    //if no value, reveal error message
      displayErr = true;
    }
    else if( element.value=="other" ) //display if other is checked
    {
      document.getElementById( "otherTitle" ).style.display="inline-block";
    }
    else if(element.value != "other") //don't display anything if title is not other
    {
      document.getElementById( "otherTitle" ).style.display="none";
      document.getElementById("titleOther").value = "";
    }
    break;

    case "titleOther":
    if( element.value=="" ) //display error if no title value
    {
      result = false;
      displayErr = true;
    }
    break;

    case "fname":
    case "lname":
    regexp = /^[a-zA-Z\-\s]+$/; //I got the format from the textbook and tweaked it
    if(element.value == "" || !regexp.test( element.value ) ) //if failed reg-test or empty reveal error
    {
      result = false;
      displayErr = true;
    }
    break;

    case "dob":
    if(element.value == "") //if no date of birth reveal error
    {
      result = false;
      displayErr=true;
    }
    break;

    case "perPhone":
    case "emMobPhone":
    case "kinMobPhone":
    regexp = /^(?=\d{10}$)(04)\d+/; //stack overflow
    if( !regexp.test( element.value ) ) //since it is not required, only display error on wrong format
    {
      result = false;
      displayErr = true;
    }
    break;

    case "wPhone":
    case "dayPhone":
    case "emDayPhone":
    case "kinDayPhone":
    regexp = /^\d{8}$|^\d{10}$/; //stack overflow
    if( !regexp.test( element.value ) ) //if format wrong display error
    {
      result = false;
      displayErr = true;
    }
    break;

    case "address":
    regexp = /^[a-zA-Z\d-\s\/]+$/; //I got the format from the textbook and tweaked it
    if( !regexp.test( element.value ) )
    {
      result = false;
      displayErr = true;
    }
    break;

    case "suburb":
    regexp = /^[a-zA-Z-\s]+$/; //I got the format from the textbook and tweaked it
    if( !regexp.test( element.value ) ) //
    {
      result = false;
      displayErr = true;
    }
    break;

    case "postCode":
    regexp = /^\d{4}$/;  //I got the format from the textbook and tweaked it
    if (!regexp.test( element.value ) ) //if wrong format send error
    {
      result = false;
      displayErr = true;
    }
    break;

    case "email":
    regexp = /^[\w_\-\.]+\@[\w_\-\.]+\.[a-z]{2,}$/; //I saw this from a youtube video somewhere can't remember
    regexp2 = /\.\.+/; //I saw this from a youtube video somewhere can't remember
    if(!regexp.test( element.value ) || regexp2.test( element.value ) ) //if wrong format display error
    {
      result = false;
      displayErr = true;
    }
    break;

    case "emName":
    case "kinName":
    regexp = /^[a-zA-Z\-\s]+$/; //I got the format from the textbook and tweaked it
    if( !regexp.test( element.value ) ) //display error if wrong format
    {
      result = false;
      displayErr = true;
    }
    break;

    case "asAboveYes":
    if( element.checked ) //if checked copies information from emergency contact to kin
    {
      document.getElementById("nextOfKin").style.display="none";
      var name =  document.getElementById("emName").value;
      var relationship = document.getElementById("emRel").value;
      var mob = document.getElementById("emMobPhone").value;
      var dayphone = document.getElementById("emDayPhone").value;

      document.getElementById("kinName").value = name;
      document.getElementById("kinRel").value = relationship;
      document.getElementById("kinMobPhone").value = mob;
      document.getElementById("kinDayPhone").value = dayphone;

    }
    else if( !element.checked ) //if it changes to not checked, it clears the values before
    {
      document.getElementById("nextOfKin").style.display="inline-block";
      document.getElementById("kinName").value = "";
      document.getElementById("kinRel").value = "";
      document.getElementById("kinMobPhone").value = "";
      document.getElementById("kinDayPhone").value = "";
    }
    break;

    default:
    result = true;
    displayErr = false;
  }

  if( displayErr == true && element.id != "asAboveYes" ) //as above does not require inline-display
  {
    document.getElementById(element.id + 'Inv').style.display="inline-block";
    element.style.borderColor ="red";
  }
  else if(displayErr == false && element.id != "asAboveYes"  ) //does not have error message with it
  {
    document.getElementById(element.id + 'Inv').style.display="none";
    element.style.borderColor ="#ccc";
  }

  return result;
}
