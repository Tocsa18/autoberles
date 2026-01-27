function input_kotelezo(id) {
  const elem = document.getElementById(id);

  // Ha üres → kötelező osztályt visszarakja
  if (elem.value.trim() === '') {
    elem.classList.add('kotelezo');
  } 
  // Ha van adat → kötelező osztályt leveszi
  else {
    elem.classList.remove('kotelezo');
  }
}

window.addEventListener('DOMContentLoaded', function() {
  document.querySelectorAll('.input').forEach(function(elem) {
    input_kotelezo(elem.id); // minden inputot ellenőriz betöltéskor
  });
});