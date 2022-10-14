// FICHIER TEMPORAIRE : Pour l'instant tout est commenté, mais ça changera dans les tâches suivantes

// Pour chaque champ, ne pas autoriser le submit si :
// - Un champ est vide
// - Le mdp ou mail de confirmation n'est pas identique à l'original
// Après un submit Vérifier les formats des champs

/***** CONNEXION *****/

/*
const loginMail = document.getElementById("loginMail");
loginMail.addEventListener("focusout", handleInputFocusOut);
// loginMail.classList.add("--error");

// <p class="profile-form__error">Erreur !</p>
// htmlentities()
// htmlspecialchars()
// strip_tags()

function handleInputFocusOut(event) {
  let errorMsg;
  const inputValue = this.value;

  console.log(inputValue);

  if (this.value.length === 0) {
    errorMsg = "Champ obligatoire";
    //   } else if (this.id === "loginMail") {
  }

  this.classList.add("--error");
  const newP = document.createElement("p");
  const newContent = document.createTextNode("Erreur!");
  newP.appendChild(newContent);
  newP.classList.add("profile-form__error");
  this.insertAdjacentElement("afterend", newP);
}

*/