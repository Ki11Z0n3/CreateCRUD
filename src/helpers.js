export const range = (start, end, step=1, ends = true) => {
    if( start < end) {
        if (ends) {
            return Array.from(Array(end + 1 - start), (_,x) => x + step + start -1);
        }
        return Array.from(Array(end - (start + 1)), (_,x) => x + step  + start);
    }
    return [];
}

export const clean = (obj) => {
    for (var propName in obj) {
        if (obj[propName] === null || obj[propName] === '' || obj[propName] === undefined) {
            delete obj[propName];
        }
    }
    return obj;
}

export const catchErrors = async (response) => {
    if (!response.ok) {
        const data = await response.json();
        throw new Error(JSON.stringify(data));
    }
    return response.json();
}

export const handleErrors = (error)  => {
    const errorData = tryParseJSON(error.message) || {};
    console.log(error.message);
    Vue.swal({
        title: errorData.title || "Error!",
        text: errorData.message || 'Ha ocurrido un error, consulte con su administrador',
        type: errorData.type || "error",
        confirmButtonClass: "btn btn--primary",
        confirmButtonText: "OK",
        customClass: "mil-modal--confirmation",
        buttonsStyling: false
    });
    return errorData;
}

export const successHandler = (response, route = null) => {
    Vue.swal({
        title: response.title || "Correcto!",
        text: response.message,
        type: response.type || "success",
        confirmButtonClass: "btn btn--primary",
        confirmButtonText: "OK",
        customClass: "mil-modal--confirmation",
    }).then((result) => {
        if (result.value && route != null) {
            window.location.href = route;
        }
    })
}

export const tryParseJSON = (jsonString) => {
    try {
        const obj = JSON.parse(jsonString);
        if (obj && typeof obj  === "object") {
            return obj;
        }
    }
    catch (e) { }
    return null;
};

export const ucFirst = (string) => {
    return `${string.charAt(0).toUpperCase()}${string.toLowerCase().slice(1)}`;
}

export const validateEmail = (email) => {
    var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(String(email.trim()).toLowerCase());
}
