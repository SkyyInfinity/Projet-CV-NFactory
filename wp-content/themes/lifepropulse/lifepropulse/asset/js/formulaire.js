
const formBtn1 = document.querySelector("#btn-1")

const formBtnPrev2 = document.querySelector("#btn-2-prev")
const formBtnNext2 = document.querySelector("#btn-2-next")

const formBtnPrev3 = document.querySelector("#btn-3-prev")
const formBtnNext3 = document.querySelector("#btn-3-next")

const formBtnPrev4 = document.querySelector("#btn-4-prev")
const formBtnNext4 = document.querySelector("#btn-4-next")

const formBtnPrev5 = document.querySelector("#btn-5-prev")
const formBtnNext5 = document.querySelector("#btn-5-next")

const formBtn6 = document.querySelector("#btn-6")


// Button listener of form 1  BTN SUIVANT 1
formBtn1.addEventListener("click", function(e) {
    // console.log('coucou3');
    gotoNextForm(formBtn1, formBtnNext2, 1, 2)
    e.preventDefault()
  })
  
  // Next button listener of form 2  BTN SUIVANT 2
  formBtnNext2.addEventListener("click", function(e) {
    gotoNextForm(formBtnNext2, formBtn3, 2, 3)
    e.preventDefault()
  })
  
  // Previous button listener of form 2 PRECEDANT 2
  formBtnPrev2.addEventListener("click", function(e) {
    gotoNextForm(formBtnPrev2, formBtn1, 2, 1)
    e.preventDefault()
  })
  
  // Next button listener of form 2  BTN SUIVANT 3
  formBtnNext3.addEventListener("click", function(e) {
    gotoNextForm(formBtnNext3, formBtn3, 2, 3)
    e.preventDefault()
  })

  // Previous button listener of form 2 PRECEDANT 3
  formBtnPrev3.addEventListener("click", function(e) {
    gotoNextForm(formBtnNext3, formBtn1, 2, 1)
    e.preventDefault()
  })



  // Button listener of form 3  BTN ENVOYEZ 3
  formBtn3.addEventListener("click", function(e) {
    document.querySelector(`.step--5`).classList.remove("step-active")
    document.querySelector(`.step--6`).classList.add("step-active")
    formBtn3.parentElement.style.display = "none"
    document.querySelector(".form--message").innerHTML = `
     <h1 class="form--message-text">Votre CV a bien etait enregistrer.</h1>
     `
    e.preventDefault()
  })

  const gotoNextForm = (prev, next, stepPrev, stepNext) => {
    // console.log('init');
    // Get form through the button
    const prevForm = prev.parentElement
    const nextForm = next.parentElement
    const nextStep = document.querySelector(`.step--${stepNext}`)
    const prevStep = document.querySelector(`.step--${stepPrev}`)
    // Add active/inactive classes to both previous and next form
    nextForm.classList.add("form-active")
    nextForm.classList.add("form-active-animate")
    prevForm.classList.add("form-inactive")
    // Change the active step element
    prevStep.classList.remove("step-active")
    nextStep.classList.add("step-active")
    // Remove active/inactive classes to both previous an next form
    setTimeout(() => {
      prevForm.classList.remove("form-active")
      prevForm.classList.remove("form-inactive")
      nextForm.classList.remove("form-active-animate")
    }, 1000)
  }

// console.log('coucou2');