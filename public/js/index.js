const defaults = {
    spread: 360,
    ticks: 50,
    gravity: 0,
    decay: 0.94,
    startVelocity: 30,
    shapes: ["star"],
    colors: ["FFE400", "FFBD00", "E89400", "FFCA6C", "FDFFB8"],
  };
  
function shoot() {
    confetti({
        ...defaults,
        particleCount: 40,
        scalar: 1.2,
        shapes: ["star"],
    });

    confetti({
        ...defaults,
        particleCount: 10,
        scalar: 0.75,
        shapes: ["circle"],
    });
}

let currentQuestion = 1;
let answerSelected = false;
let correctAnswersCount = 0;

function showNextQuestion() {
    const currentCard = document.getElementById('question' + currentQuestion);
    const nextCard = document.getElementById('question' + (currentQuestion + 1));

    // Verificar respuesta seleccionada (si la hay)
    if (answerSelected) {
        const selectedAnswer = currentCard.querySelector('.answer.selected');
        const isCorrect = selectedAnswer.getAttribute('data-correct') == 1;
        selectedAnswer.classList.add(isCorrect ? 'correct' : 'incorrect');
        currentCard.querySelectorAll('.answer').forEach(answer => answer.classList.add('disabled'));
        currentCard.querySelector('.next-button').style.display = 'none';
        answerSelected = false;
        // Incrementar el contador de respuestas correctas si es una respuesta correcta
        if (isCorrect) {
            correctAnswersCount++;
        }
    }

    // Mostrar la siguiente pregunta (o finalizar si no hay más preguntas)
    if (nextCard) {
        currentCard.style.display = 'none';
        nextCard.style.display = 'block';
        currentQuestion++;
    } else {
        let respuestasIncorrectas = currentQuestion - correctAnswersCount;
        let mensaje = correctAnswersCount >= 7 ? 'Eres un maestro pokemon.' : 'Más suerte para la próxima.';
        let html = `
            <p>Resumen de juego</p> 
            <p>Respuestas Correctas: ${correctAnswersCount}</p>
            <p>Respuestas Incorrectas: ${respuestasIncorrectas}</p>
            <p>${mensaje}</p>
        `;
        if(correctAnswersCount > 5){
            setTimeout(shoot, 0);
            setTimeout(shoot, 100);
            setTimeout(shoot, 200);
        }
        Swal.fire({
            title: '¡Gracias por jugar!',
            html: html,
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Volver a jugar',
            // cancelButtonText: 'Cancelar'
            showClass: {
                popup: 'animate__animated animate__fadeInDown'
            },
            hideClass: {
                popup: 'animate__animated animate__fadeOutUp'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // Reiniciar el juego si el usuario quiere volver a jugar
                location.reload();
            } else {
                // Redirigir al usuario al inicio si cancela
                window.location.href = '/';
            }
        })
    }
}

// Escuchar el evento de clic en las respuestas y marcar la seleccionada
const answers = document.querySelectorAll('.answer');
answers.forEach(answer => {
    answer.addEventListener('click', () => {
        if (!answerSelected) {
            answers.forEach(answer => answer.classList.remove('selected'));
            answer.classList.add('selected');
            answerSelected = true;
            const currentCard = document.getElementById('question' + currentQuestion);
            currentCard.querySelector('.next-button').style.display = 'block';
            const isCorrect = answer.getAttribute('data-correct') == 1;
            if (isCorrect) {
                answer.classList.add('correct');
            } else {
                answer.classList.add('incorrect');
            }
        }
    });
});

  
$(document).ready(function() {
    const baseUrl = window.location.protocol + '//' + window.location.host;
    $('#select-pokemon').select2();

    $('#select-pokemon').on('change', function() {
        var selectedPokemon = $(this).val();
        if (selectedPokemon) {
            window.location.href = baseUrl+ '/buscar-type' + '?type=' + selectedPokemon;
        }
    });
});