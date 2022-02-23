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
      sort-by="result_description"
      class="elevation-3 shadow p-3 mt-3"
    >
      <template v-slot:top>
        <v-toolbar flat>
          <v-toolbar-title>Resultados</v-toolbar-title>
          <v-spacer></v-spacer>
          <v-dialog v-model="dialog" max-width="600px" persistent>
            <template v-slot:activator="{ on, attrs }">
              <v-row>
                <v-col align="end">
                  <v-btn
                    class="mb-2 btn-normal"
                    v-bind="attrs"
                    v-on="on"
                    rounded
                  >
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
                    <!-- Result -->
                    <v-col cols="12" sm="6" md="12">
                      <base-text-area
                        label="Resultado"
                        v-model.trim="$v.editedItem.result_description.$model"
                        :validation="$v.editedItem.result_description"
                        validationTextType="default"
                        :validationsInput="{
                          required: true,
                          minLength: true,
                          maxLength: true,
                        }"
                        :min="1"
                        :max="500"
                        :rows="2"
                      />
                    </v-col>
                    <!-- Result -->
                    <!-- Responsible Name -->
                    <v-col cols="12" sm="12" md="12">
                      <base-input
                        label="Responsable"
                        v-model="$v.editedItem.responsible_name.$model"
                        :validation="$v.editedItem.responsible_name"
                        validationTextType="default"
                        :validationsInput="{
                          required: true,
                          minLength: true,
                          maxLength: true,
                        }"
                      />
                    </v-col>
                    <!-- Responsible Name -->
                    <!-- Users -->
                    <v-col cols="12" sm="6" md="6">
                      <base-select
                        label="Usuario"
                        v-model.trim="$v.editedItem.user_name.$model"
                        :items="users"
                        item="user_name"
                        :validation="$v.editedItem.user_name"
                      />
                    </v-col>
                    <!-- Users -->
                    <!-- Indicator -->
                    <v-col cols="12" sm="6" md="6">
                      <base-select
                        label="Indicador"
                        v-model.trim="$v.editedItem.indicator_name.$model"
                        :items="indicators"
                        item="indicator_name"
                        :validation="$v.editedItem.indicator_name"
                      />
                    </v-col>
                    <!-- Indicator -->
                    <!-- Organizational Unit -->
                    <v-col cols="12" sm="6" md="6">
                      <base-select
                        label="Unidad organizativa"
                        v-model.trim="$v.editedItem.ou_name.$model"
                        :items="organizationalUnits"
                        item="ou_name"
                        :validation="$v.editedItem.ou_name"
                      />
                    </v-col>
                    <!-- Organizational Unit -->
                    <!-- Axis -->
                    <v-col cols="12" sm="6" md="6">
                      <base-select
                        label="Eje"
                        v-model.trim="$v.editedItem.axis_description.$model"
                        :items="axisCuscas"
                        item="axis_description"
                        :validation="$v.editedItem.axis_description"
                      />
                    </v-col>
                    <!-- Axis -->
                    <!-- Percentage -->
                    <v-col cols="12" sm="12" md="6">
                      <base-input
                        label="Porcentaje"
                        v-model.trim="$v.editedItem.percentage.$model"
                        :validation="$v.editedItem.percentage"
                        type="number"
                        :validationsInput="{
                          required: true,
                        }"
                      />
                    </v-col>
                    <!-- Percentage -->
                    <!-- Date -->
                    <v-col cols="12" xs="12" sm="12" md="6">
                      <base-input
                        label="Fecha de creación"
                        v-model.trim="$v.editedItem.create_date.$model"
                        :validation="$v.editedItem.create_date"
                        type="date"
                        :validationsInput="{
                          required: true,
                        }"
                      />
                    </v-col>
                    <!-- Date -->
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
import userApi from "../apis/userApi";
import organizationalUnitApi from "../apis/organizationalUnitApi";
import indicatorApi from "../apis/indicatorApi";
import axisCuscaApi from "../apis/axisCuscaApi";
import resultsCuscaApi from "../apis/resultsCuscaApi";
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
      { text: "RESULTADO", value: "result_description" },
      { text: "RESPONSABLE", value: "responsible_name" },
      { text: "%", value: "percentage" },
      { text: "FECHA DE CREACIÓN", value: "create_date" },
      { text: "EJE", value: "axis_description" },
      { text: "USUARIO", value: "user_name" },
      { text: "INDICADOR", value: "indicator_name" },
      { text: "UNIDAD ORGANIZATIVA", value: "ou_name" },
      { text: "ACCIONES", value: "actions", sortable: false },
    ],
    records: [],
    recordsFiltered: [],
    editedIndex: -1,
    editedItem: {
      result_description: "",
      responsible_name: "",
      percentage: "",
      create_date: "",
      user_name: "",
      axis_description: "",
      indicator_name: "",
      ou_name: "",
    },
    defaultItem: {
      result_description: "",
      responsible_name: "",
      percentage: "",
      create_date: "",
      user_name: "",
      axis_description: "",
      indicator_name: "",
      ou_name: "",
    },

    textAlert: "",
    alertEvent: "success",
    showAlert: false,
    users: [],
    axisCuscas: [],
    indicators: [],
    organizationalUnits: [],
    redirectSessionFinished: false,
  }),

  // Validations
  validations: {
    editedItem: {
      result_description: {
        required,
        minLength: minLength(1),
        maxLength: maxLength(500),
      },
      responsible_name: {
        required,
        minLength: minLength(1),
        maxLength: maxLength(150),
      },
      percentage: {
        required,
        minLength: minLength(1),
        maxLength: maxLength(10),
      },
      user_name: {
        required,
      },
      axis_description: {
        required,
      },
      indicator_name: {
        required,
      },
      ou_name: {
        required,
      },
      create_date: {
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
        resultsCuscaApi.get(),
        userApi.get(null, {
          params: { skip: 0, take: 200 },
        }),
        axisCuscaApi.get(),
        organizationalUnitApi.get(),
        indicatorApi.get(),
      ];
      let responses = await Promise.all(requests).catch((error) => {
        this.updateAlert(true, "No fue posible obtener los registros.", "fail");
        this.redirectSessionFinished = lib.verifySessionFinished(
          error.response.status,
          401
        );
      });

      if (responses && responses[0].data.message == "success") {
        this.records = responses[0].data.resultsCusca;
        this.users = responses[1].data.users;
        this.axisCuscas = responses[2].data.axisCuscas;
        this.organizationalUnits = responses[3].data.organizationalUnits;
        this.indicators = responses[4].data.indicators;
        // console.log(responses[4].data);

        // this.editedItem.user_name = this.users[0].user_name;
        this.recordsFiltered = this.records;
      }
    },

    editItem(item) {
      this.dialog = true;
      this.editedIndex = this.recordsFiltered.indexOf(item);
      this.editedItem = Object.assign({}, item);
      this.$v.editedItem.user_name.$model = this.editedItem.user_name;
      this.$v.editedItem.axis_description.$model =
        this.editedItem.axis_description;
      this.$v.editedItem.indicator_name.$model = this.editedItem.indicator_name;
      this.$v.editedItem.ou_name.$model = this.editedItem.ou_name;
    },

    deleteItem(item) {
      this.editedIndex = this.recordsFiltered.indexOf(item);
      this.editedItem = Object.assign({}, item);
      this.dialogDelete = true;
    },

    async deleteItemConfirm() {
      const res = await resultsCuscaApi
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
      if (this.$v.$invalid || this.editedItem.user_name == "") {
        this.updateAlert(true, "Campos obligatorios.", "fail");

        return;
      }

      if (this.editedIndex > -1) {
        const res = await resultsCuscaApi
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
        const res = await resultsCuscaApi
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
          for (let i = 0; i < record.result_description.length; i++) {
            searchConcat += record.result_description[i].toUpperCase();
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
  },
};
</script>

