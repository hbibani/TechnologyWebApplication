// Heja Bibani
// Student ID: 16301173
// Class: Tues 4:00 pm

function formValid(form)
{
  var valid= true;

  for( var i = 0 ; i < form.length-1 ; i++ )
  {
    var check = true;

    switch( form[ i ].id )
    {
      //this is on the search page
      case "keyword":
      if(document.getElementById(form[i].id).value=="")
      {
        valid = false;
        document.getElementById("submitInv").style.display="inline-block";
      }
      break;

      //playlist page section
      case "playlistname":
      case "song1":
      if(document.getElementById(form[i].id).value=="")
      {
        valid = false;
        document.getElementById(form[i].id + 'Inv').style.display="inline-block";
      }
      break;


      //playlist page section
      case "playlistidadd":
      case "addsong":
      if(document.getElementById("playlistidadd").value=="" || document.getElementById("addsong").value=="")
      {
        valid = false;
        document.getElementById('addsongInv').style.display="inline-block";
      }
      break;
      default:
    }
  }

  return valid;
}
