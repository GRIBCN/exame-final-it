// SelectElementJS.js
class SelectElementJS {
    constructor(id, name, className = '', required = false, multiple = false) {
        this.id = id;
        this.name = name;
        this.className = className;
        this.required = required;
        this.multiple = multiple;
        this.options = [];
        this.defaultOption = null;
        this.attributes = {};
    }

    setDefaultOption(text, value = '') {
        this.defaultOption = { text, value };
    }

    addOption(value, text, selected = false) {
        this.options.push({ value, text, selected });
    }

    addAttribute(key, value) {
        this.attributes[key] = value;
    }

    render() {
        let select = `<select id="${this.id}" name="${this.name}" class="${this.className}"`;

        if (this.required) {
            select += ' required';
        }

        if (this.multiple) {
            select += ' multiple';
        }

        for (let key in this.attributes) {
            if (this.attributes.hasOwnProperty(key)) {
                select += ` ${key}="${this.attributes[key]}"`;
            }
        }

        select += '>';

        if (this.defaultOption) {
            select += `<option value="${this.defaultOption.value}">${this.defaultOption.text}</option>`;
        }

        this.options.forEach(option => {
            const selected = option.selected ? 'selected' : '';
            select += `<option value="${option.value}" ${selected}>${option.text}</option>`;
        });

        select += '</select>';
        return select;
    }
}

// Exportar la clase para usarla en otros archivos JS
export default SelectElementJS;
