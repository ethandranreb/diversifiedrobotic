filterControl = {
    values : {},
    errors : [],
    temp : {},
    reset : function () {
        filterControl.values = {};
        filterControl.errors = [];
        filterControl.temp = {};
    },
    filter : function (id, value, exp, varName, msg1, msg2, emptyAllowed = false, isEmail = false) {
        if (value) {
            if (isEmail) {
                if (exp.test(value)) {
                    filterControl.temp[varName] = value;
                } else {
                    filterControl.errors.push(id);
                    filterControl.errors.push(msg1);
                }
            }
            else if (exp.test(value)) {
                filterControl.errors.push(id);
                filterControl.errors.push(msg1);
            }
            else {
                filterControl.temp[varName] = value;
            }
        } else {
            if (emptyAllowed) {
                filterControl.temp[varName] = value;
            } else {
                filterControl.errors.push(id);
                filterControl.errors.push(msg2);
            }
        }
    }
}