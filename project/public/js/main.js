let userCurp = null


const getUser = async () => {
  document.getElementById('response').style.display = 'none'
  document.getElementById('pending').style.display = 'none'
  document.getElementById('accepted').style.display = 'none'
  document.getElementById('declined').style.display = 'none'
  const curp = document.getElementById('curp').value
  if(curp.length == 0 || curp.length < 18) {
    appendAlert('Debes ingresar un curp valido', 'danger')
    return
  }
  userCurp = curp
  try {
    const userResponse = await fetch(`/${curp}`, {
      method: 'GET',
      headers: {
        'Content-Type': 'application/json',
      }
    })

    if(!userResponse.ok) {
      throw new Error(`HTTP error! Status: ${userResponse.status}`);
    }

    const userData = await userResponse.json()
    setUpData(userData)
  } catch (error) {
    appendAlert('No se encontro un usuario con el curp indicado', 'danger')
  }
}


const setUpData = (user) => {
  document.getElementById('response').style.display = 'block'
  
  if(user.attendance == null) {
    document.getElementById('pending').style.display = 'block'
  }

  if(user.attendance == true) {
    document.getElementById('accepted').style.display = 'flex'
    document.getElementById('pdf').href = `/pdf/${userCurp}/invite`    
  }

  if(user.attendance == false) {
    document.getElementById('declined').style.display = 'flex'
  }
  
  document.getElementById('name').textContent = `Invitado ${user.name}`
  document.getElementById('award').textContent = `Distincion ${user.award}`
  document.getElementById('school').textContent = `Dependencia ${user.school}`
}

const acceptInvite = async () => {
  try {
    const companion = document.getElementById('invite').value
    const condition = document.getElementById('condition').value
    const body = {
      companion: companion == '1' ? true : false,
      condition
    }
    console.log(body);
    const response = await fetch(`/accept/${userCurp}`, {
      method: 'PUT',
      body: JSON.stringify(body)
    })

    console.log(await response.json());

    if(!response.ok) {
      throw new Error(`HTTP error! Status: ${response.status}`);
    }
    document.getElementById('pending').style.display = 'none'
    document.getElementById('accepted').style.display = 'flex'
    document.getElementById('pdf').href = `/pdf/${userCurp}/invite`
  } catch (error) {
    console.log(error);
    appendAlert('Ocurrio un error intentalo mas tarde', 'danger')
  }
}

const cancelInvite = async () => {
  try {
    const response = await fetch(`/${userCurp}/cancel`, {
      method: 'PUT',
    })

    if(!response.ok) {
      throw new Error(`HTTP error! Status: ${response.status}`);
    }
    document.getElementById('pending').style.display = 'none'
    document.getElementById('declined').style.display = 'flex'
  } catch (error) {
    console.log(error);
    appendAlert('Ocurrio un error intentalo mas tarde', 'danger')
  }
}

const appendAlert = (message, type) => {
  const alertPlaceholder = document.getElementById('liveAlertPlaceholder')
  const wrapper = document.createElement('div')
  wrapper.innerHTML = [
    `<div class="alert alert-${type} alert-dismissible" role="alert">`,
    `   <div>${message}</div>`,
    '   <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>',
    '</div>'
  ].join('')

  alertPlaceholder.append(wrapper)
}
