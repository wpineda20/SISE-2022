import userApi from "../../apis/userApi";
import resultsCuscaApi from "../../apis/resultsCuscaApi";
//import monthApi from "../../apis/monthApi";
//import yearApi from "../../apis/yearApi";
import actionsCuscaApi from "../../apis/actionsCuscaApi";
import lib from "../../libs/function";
//import moment from "moment"

export default {
    async initialize() {
      this.records = [];
      this.recordsFiltered = [];

      let requests = [
        actionsCuscaApi.get(),
        userApi.get(null, {
          params: { skip: 0, take: 200 },
        }),
        resultsCuscaApi.get(),
        //yearApi.get(),
        //monthApi.get(),
        userApi.get("/actualUserRole"),
        userApi.post("/actualUser"),
      ];
      let responses = await Promise.all(requests).catch((error) => {
        this.updateAlert(true, "No fue posible obtener los registros.", "fail");
        this.redirectSessionFinished = lib.verifySessionFinished(
          error.response.status,
          401
        );
      });

      if (responses && responses[0].data.message == "success") {
        this.records = responses[0].data.actionsCusca;
        this.users = responses[1].data.users;
        this.resultsCusca = responses[2].data.resultsCusca;
        //this.years = responses[3].data.years;
        //this.months = responses[4].data.months;
        console.log(responses);
        this.actualUser = responses[3].data.user;

        this.recordsFiltered = this.records;
      }
    },

    editItem(item) {
      this.dialog = true;
      this.editedIndex = this.recordsFiltered.indexOf(item);
      this.editedItem = Object.assign({}, item);
      this.$v.editedItem.user_name = item.user_name;
      this.$v.editedItem.result_description = item.result_description;
      //this.$v.editedItem.month_name = item.month_name;
      //this.$v.editedItem.year_name = item.year_name;
     
    },

    deleteItem(item) {
      this.editedIndex = this.recordsFiltered.indexOf(item);
      this.editedItem = Object.assign({}, item);
      this.dialogDelete = true;
    },

    async deleteItemConfirm() {
      const res = await actionsCuscaApi
        .delete(`/${this.editedItem.id}`)
        .catch((error) => {
          this.updateAlert(
            true,
            "No fue posible eliminar el registros.",
            "fail"
          );
          this.close();
        });

      if (res.data.message == "success") {
        this.redirectSessionFinished = lib.verifySessionFinished(
          res.status,
          200
        );
        this.updateAlert(true, "Registro eliminado.", "success");
      } else {
        this.updateAlert(true, "No se pudo eliminar el registro.", "fail");
      }

      this.initialize();
      this.closeDelete();
    },

    close() {
      this.dialog = false;
      this.$nextTick(() => {
        this.editedItem = this.defaultItem;
        this.editedIndex = -1;
      });
    },

    closeDelete() {
      this.$nextTick(() => {
        this.editedItem = this.defaultItem;
        this.editedIndex = -1;
      });

      this.dialogDelete = false;
    },

    async save() {
      this.$v.$touch();
      if (this.$v.$invalid || this.editedItem.result_description == "") {
        this.updateAlert(true, "Campos obligatorios.", "fail");

        return;
      }

      if (this.editedIndex > -1) {
        const res = await actionsCuscaApi
          .put(`/${this.editedItem.id}`, this.editedItem)
          .catch((error) => {
            this.updateAlert(true, "No fue posible crear el registro.", "fail");
            this.close();
            this.redirectSessionFinished = lib.verifySessionFinished(
              error.response.status,
              419
            );
          });

        if (res.data.message == "success") {
          this.updateAlert(
            true,
            "Registro actualizado correctamente.",
            "success"
          );
        }
      } else {
        const res = await actionsCuscaApi
          .post(null, this.editedItem)
          .catch((error) => {
            this.updateAlert(true, "No fue posible crear el registro.", "fail");
            this.close();
          });

        if (res.data.message == "success") {
          this.updateAlert(
            true,
            "Registro almacenado correctamente.",
            "success"
          );
        }
      }
      this.close();
      this.initialize();
    },

    searchValue() {
      this.recordsFiltered = [];

      if (this.search != "") {
        this.records.forEach((record) => {
          let searchConcat = "";
          for (let i = 0; i < record.action_description.length; i++) {
            searchConcat += record.action_description[i].toUpperCase();
            if (
              searchConcat === this.search.toUpperCase() &&
              !this.recordsFiltered.some((rec) => rec == record)
            ) {
              this.recordsFiltered.push(record);
            }
          }
        });
        return;
      }

      this.recordsFiltered = this.records;
    },

    updateAlert(show = false, text = "Alerta", event = "success") {
      this.textAlert = text;
      this.alertEvent = event;
      this.showAlert = show;
    },

    updateTimeOut(event) {
      this.redirectSessionFinished = event;
    },

    openModal() {
      this.dialog = true;
      this.editedItem.result_description =
      this.resultsCusca[0].result_description;
      this.editedItem.user_name = this.actualUser.user_name;
      //this.editedItem.year_name = new Date().getFullYear();

      //const month_name = moment().format("MMMM");
      //this.editedItem.month_name = month_name.charAt(0).toUpperCase() + month_name.slice(1);

      this.editedItem.action_description = "";
      this.editedItem.responsable_name = "";
      this.editedItem.annual_actions = 0;
      this.editedItem.budget_executed = 0;
      this.editedItem.verification_method ="";
      this.editedItem.data_source ="";
      this.editedItem.executed = false;
    },

  }