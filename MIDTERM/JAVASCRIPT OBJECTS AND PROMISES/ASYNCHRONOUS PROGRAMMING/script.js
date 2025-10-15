// Section A: Promise that rejects if input is empty, resolves with the input
function setupSectionA() {
    const inputA = document.createElement('input');
    inputA.placeholder = 'Enter your name';
    inputA.style.margin = '10px';
    inputA.style.padding = '8px';
    inputA.style.width = '200px';
    document.body.appendChild(inputA);

    const buttonA = document.createElement('button');
    buttonA.textContent = 'Submit A';
    buttonA.style.padding = '10px 20px';
    buttonA.style.backgroundColor = '#4CAF50';
    buttonA.style.color = 'white';
    buttonA.style.border = 'none';
    buttonA.style.borderRadius = '5px';
    buttonA.style.cursor = 'pointer';
    document.body.appendChild(buttonA);

    const outputA = document.createElement('div');
    outputA.style.margin = '10px';
    outputA.style.padding = '10px';
    outputA.style.border = '1px solid #ccc';
    outputA.style.backgroundColor = '#e9e9e9';
    outputA.style.borderRadius = '5px';
    document.body.appendChild(outputA);

    buttonA.addEventListener('click', () => {
        const name = inputA.value.trim();
        const promise = new Promise((resolve, reject) => {
            if (name === '') {
                reject('Name is required');
            } else {
                resolve(name);
            }
        });

        promise.then(result => {
            outputA.textContent = `good day, ${result}!`;
        }).catch(error => {
            outputA.textContent = error;
        });
    });
}

// Section B: Promise that rejects if input is empty, resolves after 5 seconds
function setupSectionB() {
    const inputB = document.createElement('input');
    inputB.placeholder = 'Enter your name';
    inputB.style.margin = '10px';
    inputB.style.padding = '8px';
    inputB.style.width = '200px';
    document.body.appendChild(inputB);

    const buttonB = document.createElement('button');
    buttonB.textContent = 'Submit B';
    buttonB.style.padding = '10px 20px';
    buttonB.style.backgroundColor = '#4CAF50';
    buttonB.style.color = 'white';
    buttonB.style.border = 'none';
    buttonB.style.borderRadius = '5px';
    buttonB.style.cursor = 'pointer';
    document.body.appendChild(buttonB);

    const outputB = document.createElement('div');
    outputB.style.margin = '10px';
    outputB.style.padding = '10px';
    outputB.style.border = '1px solid #ccc';
    outputB.style.backgroundColor = '#e9e9e9';
    outputB.style.borderRadius = '5px';
    document.body.appendChild(outputB);

    buttonB.addEventListener('click', () => {
        const name = inputB.value.trim();
        const promise = new Promise((resolve, reject) => {
            if (name === '') {
                reject('Name is required');
            } else {
                setTimeout(() => {
                    resolve(name);
                }, 5000);  
            }
        });

        promise.then(result => {
            outputB.textContent = `good day, ${result}!`;
        }).catch(error => {
            outputB.textContent = error;
        });
    });
}

// Section C: Promise that rejects if input is empty, resolves after 7 seconds
function setupSectionC() {
    const inputC = document.createElement('input');
    inputC.placeholder = 'Enter your name';
    inputC.style.margin = '10px';
    inputC.style.padding = '8px';
    inputC.style.width = '200px';
    document.body.appendChild(inputC);

    const buttonC = document.createElement('button');
    buttonC.textContent = 'Submit C';
    buttonC.style.padding = '10px 20px';
    buttonC.style.backgroundColor = '#4CAF50';
    buttonC.style.color = 'white';
    buttonC.style.border = 'none';
    buttonC.style.borderRadius = '5px';
    buttonC.style.cursor = 'pointer';
    document.body.appendChild(buttonC);

    const outputC = document.createElement('div');
    outputC.style.margin = '10px';
    outputC.style.padding = '10px';
    outputC.style.border = '1px solid #ccc';
    outputC.style.backgroundColor = '#e9e9e9';
    outputC.style.borderRadius = '5px';
    document.body.appendChild(outputC);

    buttonC.addEventListener('click', () => {
        const name = inputC.value.trim();
        const promise = new Promise((resolve, reject) => {
            if (name === '') {
                reject('Name is required');
            } else {
                setTimeout(() => {
                    resolve(name);
                }, 7000); 
            }
        });

        promise.then(result => {
            outputC.textContent = `good day, ${result}!`;
        }).catch(error => {
            outputC.textContent = error;
        });
    });
}

// Section D: Promise that rejects if input is empty, resolves with input in uppercase
function setupSectionD() {
    const inputD = document.createElement('input');
    inputD.placeholder = 'Enter your name';
    inputD.style.margin = '10px';
    inputD.style.padding = '8px';
    inputD.style.width = '200px';
    document.body.appendChild(inputD);

    const buttonD = document.createElement('button');
    buttonD.textContent = 'Submit D';
    buttonD.style.padding = '10px 20px';
    buttonD.style.backgroundColor = '#4CAF50';
    buttonD.style.color = 'white';
    buttonD.style.border = 'none';
    buttonD.style.borderRadius = '5px';
    buttonD.style.cursor = 'pointer';
    document.body.appendChild(buttonD);

    const outputD = document.createElement('div');
    outputD.style.margin = '10px';
    outputD.style.padding = '10px';
    outputD.style.border = '1px solid #ccc';
    outputD.style.backgroundColor = '#e9e9e9';
    outputD.style.borderRadius = '5px';
    document.body.appendChild(outputD);

    buttonD.addEventListener('click', () => {
        const name = inputD.value.trim();
        const promise = new Promise((resolve, reject) => {
            if (name === '') {
                reject('Name is required');
            } else {
                resolve(name.toUpperCase());  
            }
        });

        promise.then(result => {
            outputD.textContent = `good day, ${result}!`;  
        }).catch(error => {
            outputD.textContent = error;
        });
    });
}

// Section E: Promise that rejects if input is empty or less than 5 characters, resolves with uppercase
function setupSectionE() {
    const inputE = document.createElement('input');
    inputE.placeholder = 'Enter your name (at least 5 characters)';
    inputE.style.margin = '10px';
    inputE.style.padding = '8px';
    inputE.style.width = '200px';
    document.body.appendChild(inputE);

    const buttonE = document.createElement('button');
    buttonE.textContent = 'Submit E';
    buttonE.style.padding = '10px 20px';
    buttonE.style.backgroundColor = '#4CAF50';
    buttonE.style.color = 'white';
    buttonE.style.border = 'none';
    buttonE.style.borderRadius = '5px';
    buttonE.style.cursor = 'pointer';
    document.body.appendChild(buttonE);

    const outputE = document.createElement('div');
    outputE.style.margin = '10px';
    outputE.style.padding = '10px';
    outputE.style.border = '1px solid #ccc';
    outputE.style.backgroundColor = '#e9e9e9';
    outputE.style.borderRadius = '5px';
    document.body.appendChild(outputE);

    buttonE.addEventListener('click', () => {
        const name = inputE.value.trim();
        const promise = new Promise((resolve, reject) => {
            if (name === '' || name.length < 5) {
                reject('Name is required and must be at least 5 characters');
            } else {
                resolve(name.toUpperCase()); 
            }
        });

        promise.then(result => {
            outputE.textContent = `good day, ${result}!`;  
        }).catch(error => {
            outputE.textContent = error;
        });
    });
}

// Section F: Promise that rejects if input is empty or less than 5 characters, resolves with reversed input
function setupSectionF() {
    const inputF = document.createElement('input');
    inputF.placeholder = 'Enter your name (at least 5 characters)';
    inputF.style.margin = '10px';
    inputF.style.padding = '8px';
    inputF.style.width = '200px';
    document.body.appendChild(inputF);

    const buttonF = document.createElement('button');
    buttonF.textContent = 'Submit F';
    buttonF.style.padding = '10px 20px';
    buttonF.style.backgroundColor = '#4CAF50';
    buttonF.style.color = 'white';
    buttonF.style.border = 'none';
    buttonF.style.borderRadius = '5px';
    buttonF.style.cursor = 'pointer';
    document.body.appendChild(buttonF);

    const outputF = document.createElement('div');
    outputF.style.margin = '10px';
    outputF.style.padding = '10px';
    outputF.style.border = '1px solid #ccc';
    outputF.style.backgroundColor = '#e9e9e9';
    outputF.style.borderRadius = '5px';
    document.body.appendChild(outputF);

    buttonF.addEventListener('click', () => {
        const name = inputF.value.trim();
        const promise = new Promise((resolve, reject) => {
            if (name === '' || name.length < 5) {
                reject('Name is required and must be at least 5 characters');
            } else {
                const reversed = name.split('').reverse().join('');  
                resolve(reversed);
            }
        });

        promise.then(result => {
            outputF.textContent = `good day, ${result}!`;  
        }).catch(error => {
            outputF.textContent = error;
        });
    });
}

// Section G: Promise that rejects if input is not an integer, resolves with squared number
function setupSectionG() {
    const inputG = document.createElement('input');
    inputG.placeholder = 'Enter an integer';
    inputG.style.margin = '10px';
    inputG.style.padding = '8px';
    inputG.style.width = '200px';
    document.body.appendChild(inputG);

    const buttonG = document.createElement('button');
    buttonG.textContent = 'Submit G';
    buttonG.style.padding = '10px 20px';
    buttonG.style.backgroundColor = '#4CAF50';
    buttonG.style.color = 'white';
    buttonG.style.border = 'none';
    buttonG.style.borderRadius = '5px';
    buttonG.style.cursor = 'pointer';
    document.body.appendChild(buttonG);

    const outputG = document.createElement('div');
    outputG.style.margin = '10px';
    outputG.style.padding = '10px';
    outputG.style.border = '1px solid #ccc';
    outputG.style.backgroundColor = '#e9e9e9';
    outputG.style.borderRadius = '5px';
    document.body.appendChild(outputG);

    buttonG.addEventListener('click', () => {
        const value = inputG.value.trim();
        const num = parseFloat(value);
        if (isNaN(num) || !Number.isInteger(num)) {
            outputG.textContent = 'Must be an integer';
            return;
        }
        const originalNum = num;  
        const promise = new Promise((resolve, reject) => {
            resolve(num * num);  
        });

        promise.then(squared => {
            outputG.textContent = `${originalNum} is ${squared} when squared`;
        }).catch(error => {
            outputG.textContent = error;
        });
    });
}

// Section H: Promise that rejects if input is not an integer, resolves after 5 seconds with cubed number
function setupSectionH() {
    const inputH = document.createElement('input');
    inputH.placeholder = 'Enter an integer';
    inputH.style.margin = '10px';
    inputH.style.padding = '8px';
    inputH.style.width = '200px';
    document.body.appendChild(inputH);

    const buttonH = document.createElement('button');
    buttonH.textContent = 'Submit H';
    buttonH.style.padding = '10px 20px';
    buttonH.style.backgroundColor = '#4CAF50';
    buttonH.style.color = 'white';
    buttonH.style.border = 'none';
    buttonH.style.borderRadius = '5px';
    buttonH.style.cursor = 'pointer';
    document.body.appendChild(buttonH);

    const outputH = document.createElement('div');
    outputH.style.margin = '10px';
    outputH.style.padding = '10px';
    outputH.style.border = '1px solid #ccc';
    outputH.style.backgroundColor = '#e9e9e9';
    outputH.style.borderRadius = '5px';
    document.body.appendChild(outputH);

    buttonH.addEventListener('click', () => {
        const value = inputH.value.trim();
        const num = parseFloat(value);
        if (isNaN(num) || !Number.isInteger(num)) {
            outputH.textContent = 'Must be an integer';
            return;
        }
        const originalNum = num;  
        const promise = new Promise((resolve, reject) => {
            setTimeout(() => {
                resolve(num * num * num);  
            }, 5000);
        });

        promise.then(cubed => {
            outputH.textContent = `${originalNum} is ${cubed} when cubed`;
        }).catch(error => {
            outputH.textContent = error;
        });
    });
}

// Section I: Promise that rejects if input is not between 1 and 10, with failure limit
function setupSectionI() {
    let failureCountI = 0;  
    const inputI = document.createElement('input');
    inputI.placeholder = 'Enter a number between 1 and 10';
    inputI.style.margin = '10px';
    inputI.style.padding = '8px';
    inputI.style.width = '200px';
    document.body.appendChild(inputI);

    const buttonI = document.createElement('button');
    buttonI.textContent = 'Submit I';
    buttonI.style.padding = '10px 20px';
    buttonI.style.backgroundColor = '#4CAF50';
    buttonI.style.color = 'white';
    buttonI.style.border = 'none';
    buttonI.style.borderRadius = '5px';
    buttonI.style.cursor = 'pointer';
    document.body.appendChild(buttonI);

    const outputI = document.createElement('div');
    outputI.style.margin = '10px';
    outputI.style.padding = '10px';
    outputI.style.border = '1px solid #ccc';
    outputI.style.backgroundColor = '#e9e9e9';
    outputI.style.borderRadius = '5px';
    document.body.appendChild(outputI);

    buttonI.addEventListener('click', () => {
        if (failureCountI >= 3) {
            outputI.textContent = 'You already failed three times, so no chances anymore';
            return;
        }
        const value = inputI.value.trim();
        const num = parseFloat(value);
        const promise = new Promise((resolve, reject) => {
            if (isNaN(num) || num < 1 || num > 10) {
                reject('Not between 1 and 10');
            } else {
                resolve(num);
            }
        });

        promise.then(result => {
            outputI.textContent = `Yes ${result} is between 1 and 10`;
        }).catch(error => {
            failureCountI++;  
            outputI.textContent = error;
        });
    });
}

setupSectionA();
setupSectionB();
setupSectionC();
setupSectionD();
setupSectionE();
setupSectionF();
setupSectionG();
setupSectionH();
setupSectionI();
