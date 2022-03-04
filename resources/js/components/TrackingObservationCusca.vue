<template>
  <div data-app>
    <alert-time-out
      :redirect="redirectSessionFinished"
      @redirect="updateTimeOut($event)"
    />
    <alert
      :text="textAlert"
      :event="alertEvent"
      :show="showAlert"
      @show-alert="updateAlert($event)"
      class="mb-2"
    />
    <v-data-table
      :headers="headers"
      :items="recordsFiltered"
      sort-by="observation"
      class="elevation-3 shadow p-3 mt-3"
    >
      <template v-slot:top>
        <v-toolbar flat>
          <v-toolbar-title>Observaciones de Seguimientos</v-toolbar-title>
          <v-spacer></v-spacer>
          <v-dialog v-model="dialog" max-width="600px" persistent>
            <template v-slot:activator="{}">
              <v-row>
                <v-col align="end">
                  <v-btn class="mb-2 btn-normal" rounded @click="openModal">
                    Agregar
                  </v-btn>
                </v-col>
                <v-col
                  xs="6"
                  sm="6"
                  md="6"
                  class="d-none d-md-block d-lg-block"
                >
                  <v-text-field
                    dense
                    label="Buscar"
                    outlined
                    type="text"
                    class=""
                    v-model="search"
                    @keyup="searchValue()"
                  ></v-text-field>
                </v-col>
              </v-row>
            </template>
            <v-card class="flexcard" height="100%">
              <v-card-title>
                <h1 class="mx-auto pt-3 mb-3 text-center black-secondary">
                  {{ formTitle }}
                </h1>
              </v-card-title>

              <v-card-text>
                <v-container>
                  <!-- Form -->
                  <!-- User -->
                  <!-- <v-row v-if="users.length > 0"> -->
                  <v-row>
                    <!-- Observation -->
                    <v-col cols="12" sm="6" md="12">
                      <base-text-area
                        label="Observación"
                        v-model.trim="$v.editedItem.observation.$model"
                        :validation="$v.editedItem.observation"
                        validationTextType="default"
                        :validationsInput="{
                          required: true,
                          minLength: true,
                          maxLength: true,
                        }"
                        :min="1"
                        :max="500"
                        :rows="3"
                      />
                    </v-col>
                    <!-- Observation -->
                    <!-- Observation Reply -->
                    <v-col cols="12" sm="6" md="12">
                      <base-text-area
                        label="Respuesta"
                        v-model.trim="$v.editedItem.observation_reply.$model"
                        :validation="$v.editedItem.observation_reply"
                        validationTextType="default"
                        :validationsInput="{
                          required: true,
                          minLength: true,
                          maxLength: true,
                        }"
                        :min="1"
                        :max="500"
                        :rows="3"
                      />
                    </v-col>
                    <!-- Observation Reply -->
                    <!-- Year -->
                    <v-col cols="12" sm="6" md="6">
                      <base-select
                        label="Año"
                        v-model.trim="$v.editedItem.value.$model"
                        :items="years"
                        item="value"
                        :validation="$v.editedItem.value"
                      />
                    </v-col>
                    <!-- Year -->

                    <!-- Month -->
                    <v-col cols="12" sm="6" md="6">
                      <base-select
                        label="Mes"
                        v-model.trim="$v.editedItem.month_name.$model"
                        :items="months"
                        item="month_name"
                        :validation="$v.editedItem.month_name"
                      />
                    </v-col>
                    <!-- Month -->

                    <!-- Actions -->
                    <v-col cols="12" sm="6" md="6">
                      <base-select
                        label="Acción"
                        v-model.trim="$v.editedItem.action_description.$model"
                        :items="actions"
                        item="action_description"
                        :validation="$v.editedItem.action_description"
                      />
                    </v-col>
                    <!-- Actions -->
                    <!-- Reply Date -->
                    <v-col cols="12" xs="12" sm="12" md="6">
                      <base-input
                        label="Fecha de respuesta"
                        v-model.trim="$v.editedItem.reply_date.$model"
                        :validation="$v.editedItem.reply_date"
                        type="date"
                        :validationsInput="{
                          required: true,
                        }"
                      />
                    </v-col>
                    <!-- Reply Date -->
                  </v-row>
                  <!-- Form -->
                  <v-row>
                    <v-col align="center">
                      <v-btn
                        color="btn-normal no-uppercase mt-3"
                        rounded
                        @click="save"
                      >
                        Guardar
                      </v-btn>

                      <v-btn
                        color="btn-normal-close no-uppercase mt-3"
                        rounded
                        @click="close"
                      >
                        Cancelar
                      </v-btn>
                    </v-col>
                  </v-row>
                </v-container>
              </v-card-text>
            </v-card>
          </v-dialog>
          <v-dialog v-model="dialogDelete" max-width="400px">
            <v-card class="h-100">
              <v-container>
                <h3 class="black-secondary text-center mt-3 mb-3">
                  Eliminar registro
                </h3>
                <v-row>
                  <v-col align="center">
                    <v-btn
                      color="btn-normal no-uppercase mt-3 mb-3 pr-5 pl-5 mx-auto"
                      rounded
                      @click="deleteItemConfirm"
                      >Confirmar</v-btn
                    >
                    <v-btn
                      color="btn-normal-close no-uppercase mt-3 mb-3"
                      rounded
                      @click="closeDelete"
                    >
                      Cancelar
                    </v-btn>
                  </v-col>
                </v-row>
              </v-container>
            </v-card>
          </v-dialog>
        </v-toolbar>
      </template>
      <template v-slot:[`item.actions`]="{ item }">
        <v-icon small class="mr-2" @click="editItem(item)"> mdi-pencil </v-icon>
        <v-icon small @click="deleteItem(item)"> mdi-delete </v-icon>
      </template>
      <template v-slot:no-data>
        <a
          href="#"
          class="btn btn-normal-secondary no-decoration"
          @click="initialize"
        >
          Reiniciar
        </a>
      </template>
    </v-data-table>
  </div>
</template>

<script>
import trackingObservationCuscaApi from "../apis/trackingObservationCuscaApi";
import yearApi from "../apis/yearApi";
import monthApi from "../apis/monthApi";
import actionsCuscaApi from "../apis/actionsCuscaApi";
import lib from "../libs/function";
import {
  required,
  minLength,
  maxLength,
  helpers,
} from "vuelidate/lib/validators";

export default {
  data: () => ({
    search: "",
    dialog: false,
    dialogDelete: false,
    headers: [
      { text: "OBSERVACIÓN", value: "observation" },
      { text: "RESPUESTA", value: "observation_reply" },
      { text: "FECHA DE RESPUESTA", value: "reply_date" },
      { text: "ACCIÓN", value: "action_description" },
      { text: "MES", value: "month_name" },
      { text: "AÑO", value: "value" },
      { text: "ACCIONES", value: "actions", sortable: false },
    ],
    records: [],
    recordsFiltered: [],
    editedIndex: -1,
    editedItem: {
      observation: "",
      observation_reply: "",
      action_description: "",
      month_name: "",
      reply_date: "",
      value: "",
    },
    defaultItem: {
      observation: "",
      observation_reply: "",
      action_description: "",
      month_name: "",
      reply_date: "",
      value: "",
    },

    textAlert: "",
    alertEvent: "success",
    showAlert: false,
    months: [],
    years: [],
    actions: [],

    redirectSessionFinished: false,
  }),

  // Validations
  validations: {
    editedItem: {
      observation: {
        required,
        minLength: minLength(1),
        maxLength: maxLength(500),
      },
      observation_reply: {
        required,
        minLength: minLength(1),
        maxLength: maxLength(500),
      },
      month_name: {
        required,
      },
      value: {
        required,
      },
      action_description: {
        required,
      },
      reply_date: {
        required,
        isValidBirthday: helpers.regex(
          "isValidBirthday",
          /([0-9]{4}-[0-9]{2}-[0-9]{2})/
        ),
        minDate: (value) => value > new Date("1920-01-01").toISOString(),
        maxDate: () => {
          let today = new Date();
          let year = today.getFullYear() - 18;
          let date = today.setFullYear(year);
          return new Date(date).toISOString();
        },
      },
    },
  },
  // Validations
  computed: {
    formTitle() {
      return this.editedIndex === -1 ? "Nuevo registro" : "Editar registro";
    },
  },

  watch: {
    dialog(val) {
      val || this.close();
    },
    dialogDelete(val) {
      val || this.closeDelete();
    },
  },

  created() {
    this.initialize();
  },

  methods: {
    async initialize() {
      this.records = [];
      this.recordsFiltered = [];

      let requests = [
        trackingObservationCuscaApi.get(),
        yearApi.get(),
        monthApi.get(),
        actionsCuscaApi.get(),
      ];
      let responses = await Promise.all(requests).catch((error) => {
        this.updateAlert(true, "No fue posible obtener los registros.", "fail");
        this.redirectSessionFinished = lib.verifySessionFinished(
          error.response.status,
          401
        );
      });

      if (responses && responses[0].data.message == "success") {
        this.records = responses[0].data.trackingObservationsCusca;
        this.years = responses[1].data.years;
        this.months = responses[2].data.months;
        this.actions = responses[3].data.actionsCusca;
        // console.log(responses);

        this.recordsFiltered = this.records;
      }
    },

    editItem(item) {
      this.dialog = true;
      this.editedIndex = this.recordsFiltered.indexOf(item);
      this.editedItem = Object.assign({}, item);
      this.$v.editedItem.value.$model = this.editedItem.value;
      this.$v.editedItem.month_name.$model = this.editedItem.month_name;
      this.$v.editedItem.action_description.$model =
        this.editedItem.action_description;
    },

    deleteItem(item) {
      this.editedIndex = this.recordsFiltered.indexOf(item);
      this.editedItem = Object.assign({}, item);
      this.dialogDelete = true;
    },

    async deleteItemConfirm() {
      const res = await trackingObservationCuscaApi
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
      if (this.$v.$invalid || this.editedItem.value == "") {
        this.updateAlert(true, "Campos obligatorios.", "fail");

        return;
      }

      if (this.editedIndex > -1) {
        const res = await trackingObservationCuscaApi
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
        const res = await trackingObservationCuscaApi
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
          for (let i = 0; i < record.observation.length; i++) {
            searchConcat += record.observation[i].toUpperCase();
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
      this.editedItem.value = this.years[0].value;
      this.editedItem.month_name = this.months[0].month_name;
      this.editedItem.action_description = this.actions[0].action_description;
      this.editedItem.observation = "";
      this.editedItem.observation_reply = "";
      this.editedItem.reply_date = "";
    },
  },
};
</script>

