function clearTextarea() {
  let textareaForm = document.getElementById("form");
  textareaForm.value = "";
}

function copy(){
  let text = document.getElementById("copy").innerHTML;
  navigator.clipboard.writeText(text);
}
