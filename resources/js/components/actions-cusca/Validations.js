import {
    required,
    minLength,
    maxLength,
    helpers,
  } from "vuelidate/lib/validators";

const Validations = {
    editedItem: {
        action_description: {
            required,
            minLength: minLength(1),
            maxLength: maxLength(500),
          },
          annual_actions: {
            required,
            minLength: minLength(1),
            maxLength: maxLength(10),
          },
          executed: {
            required,
          },
          responsable_name: {
            required,
            minLength: minLength(1),
            maxLength: maxLength(150),
          },
          verification_method: {
            required,
            minLength: minLength(1),
            maxLength: maxLength(150),
          },
          data_source: {
            required,
            minLength: minLength(1),
            maxLength: maxLength(150),
          },
          mesure_unit: {
            required,
            minLength: minLength(1),
            maxLength: maxLength(150),
          },
          budget_executed: {
            required,
            minLength: minLength(1),
            maxLength: maxLength(10),
          },
          user_name: {
              required,
              minLength: minLength(1),
          },
          result_description: {
            required,
            minLength: minLength(1),
          },
          month_name: {
            required,
            minLength: minLength(1),
          },
          year_name: {
            required,
            minLength: minLength(1),
          },
    }
    
}

export default Validations;