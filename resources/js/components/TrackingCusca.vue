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

    <div class="container">
      <v-row>
        <v-tabs>
          <v-tab @click="filterTracking('Mensuales')">Mensuales</v-tab>
          <v-tab @click="filterTracking('Atrasados')">Atrasados</v-tab>
          <v-tab @click="filterTracking('Completados')">Completados</v-tab>
        </v-tabs>
     </v-row>
    </div>

    <v-data-table
      :headers="headers"
      :items="recordsFiltered"
      sort-by="tracking_detail"
      class="elevation-3 shadow p-3 mt-3"
    >
      <template v-slot:top>
        <v-toolbar flat>
          <v-toolbar-title>Seguimientos</v-toolbar-title>
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
                    <!-- Tracking Detail -->
                    <v-col cols="12" sm="6" md="12">
                      <base-text-area
                        label="Detalle de seguimiento"
                        v-model.trim="$v.editedItem.tracking_detail.$model"
                        :validation="$v.editedItem.tracking_detail"
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
                    <!-- Tracking Detail -->

                    <!-- Users -->
                    <!-- <v-col cols="12" sm="6" md="6">
                      <base-select
                        label="Usuario"
                        v-model.trim="$v.editedItem.user_name.$model"
                        :items="users"
                        item="user_name"
                        :validation="$v.editedItem.user_name"
                        :readonly="true"
                      />
                    </v-col> -->
                    <!-- Users -->
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
                    <!-- Tracking Status -->
                    <v-col cols="12" sm="6" md="6">
                      <base-select
                        label="Estado de seguimiento"
                        v-model.trim="$v.editedItem.status_name.$model"
                        :items="trakingStatuses"
                        item="status_name"
                        :validation="$v.editedItem.status_name"
                      />
                    </v-col>
                    <!-- Tracking Status -->
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
                    <!-- Observations -->
                    <v-col cols="12" sm="6" md="6">
                      <base-select
                        label="Observación"
                        v-model.trim="$v.editedItem.observation.$model"
                        :items="observations"
                        item="observation"
                        :validation="$v.editedItem.observation"
                      />
                    </v-col>
                    <!-- Observations -->
                    <!-- Monthly Actions -->
                    <v-col cols="12" sm="12" md="6">
                      <base-input
                        label="Acciones mensuales"
                        v-model.trim="$v.editedItem.monthly_actions.$model"
                        :validation="$v.editedItem.monthly_actions"
                        type="number"
                        :validationsInput="{
                          required: true,
                        }"
                      />
                    </v-col>
                    <!-- Monthly Actions -->
                    <!-- Bubget executed -->
                    <v-col cols="12" sm="12" md="6">
                      <base-input
                        label="Presupuesto ejecutado"
                        v-model.trim="$v.editedItem.budget_executed.$model"
                        :validation="$v.editedItem.budget_executed"
                        type="number"
                        :validationsInput="{
                          required: true,
                        }"
                      />
                    </v-col>
                    <!-- Bubget executed -->

                    <!-- Executed -->
                    <v-col cols="12" sm="6" md="6" class="pt-0">
                      <v-checkbox
                        v-model="$v.editedItem.executed.$model"
                        label="Ejecutado"
                      ></v-checkbox>
                    </v-col>
                    <!-- Executed -->
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
import yearApi from "../apis/yearApi";
import monthApi from "../apis/monthApi";
import trakingStatusApi from "../apis/trakingStatusApi";
import trackingCuscaApi from "../apis/trackingCuscaApi";
import trackingObservationCuscaApi from "../apis/trackingObservationCuscaApi";
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
      { text: "SEGUIMIENTO", value: "tracking_detail" },
      { text: "ACCIÓN", value: "action_description" },
      { text: "MES", value: "month_name" },
      { text: "AÑO", value: "value" },
      { text: "ACCIONES MENSUALES", value: "monthly_actions" },
      { text: "PRESUPUESTO", value: "budget_executed" },
      { text: "USUARIO", value: "user_name" },
      { text: "EJECUTADO", value: "executed" },
      { text: "ESTADO", value: "status_name" },
      { text: "OBSERVACIÓN", value: "observation" },
      { text: "ACCIONES", value: "actions", sortable: false },
    ],
    records: [],
    recordsFiltered: [],
    editedIndex: -1,
    editedItem: {
      tracking_detail: "",
      action_description: "",
      month_name: "",
      budget_executed: 0,
      // user_name: "",
      value: "",
      status_name: "",
      monthly_actions: 0,
      executed: false,
      observation: "",
    },
    defaultItem: {
      tracking_detail: "",
      action_description: "",
      month_name: "",
      budget_executed: 0,
      // user_name: "",
      value: "",
      status_name: "",
      monthly_actions: 0,
      executed: false,
      observation: "",
    },

    textAlert: "",
    alertEvent: "success",
    showAlert: false,
    users: [],
    months: [],
    years: [],
    actions: [],
    trakingStatuses: [],
    observations: [],

    redirectSessionFinished: false,
  }),

  // Validations
  validations: {
    editedItem: {
      tracking_detail: {
        required,
        minLength: minLength(1),
        maxLength: maxLength(500),
      },

      monthly_actions: {
        required,
        minLength: minLength(1),
        maxLength: maxLength(10),
      },
      budget_executed: {
        required,
        minLength: minLength(1),
        maxLength: maxLength(10),
      },
      // user_name: {
      //   required,
      // },
      month_name: {
        required,
      },
      value: {
        required,
      },
      action_description: {
        required,
      },
      status_name: {
        required,
      },
      executed: {
        // required,
      },
      observation: {
        required,
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
        trackingCuscaApi.get(null, {
        params: {
          filter: "Mensuales"
        }
      }),
        userApi.get(null, {
          params: { skip: 0, take: 200 },
        }),
        trakingStatusApi.get(),
        yearApi.get(),
        monthApi.get(),
        actionsCuscaApi.get(),
        trackingObservationCuscaApi.get(),
      ];
      let responses = await Promise.all(requests).catch((error) => {
        this.updateAlert(true, "No fue posible obtener los registros.", "fail");
        this.redirectSessionFinished = lib.verifySessionFinished(
          error.response.status,
          401
        );
      });

      if (responses && responses[0].data.message == "success") {
        this.records = responses[0].data.trackingsCusca;
        this.users = responses[1].data.users;
        this.trakingStatuses = responses[2].data.trakingStatuses;
        this.years = responses[3].data.years;
        this.months = responses[4].data.months;
        this.actions = responses[5].data.actionsCusca;
        this.observations = responses[6].data.trackingObservationsCusca;

        this.recordsFiltered = this.records;
      }
    },

    editItem(item) {
      this.dialog = true;
      this.editedIndex = this.recordsFiltered.indexOf(item);
      this.editedItem = Object.assign({}, item);
      // this.$v.editedItem.user_name.$model = this.editedItem.user_name;
      this.$v.editedItem.status_name.$model = this.editedItem.status_name;
      this.$v.editedItem.value.$model = this.editedItem.value;
      this.$v.editedItem.month_name.$model = this.editedItem.month_name;
      this.$v.editedItem.action_description.$model =
        this.editedItem.action_description;
      this.$v.editedItem.observation.$model = this.editedItem.observation;
    },

    deleteItem(item) {
      this.editedIndex = this.recordsFiltered.indexOf(item);
      this.editedItem = Object.assign({}, item);
      this.dialogDelete = true;
    },

    async deleteItemConfirm() {
      const res = await trackingCuscaApi
        .delete(`/${this.editedItem.id}`)
        .catch((error) => {
          this.updateAlert(
            true,
            "No fue posible eliminar el registros.",
            "fail"
          );
          this.close();
        });

      if (res.data.reason) {
        this.updateAlert(true, res.data.reason, "fail");
      }

      if (res.data.message == "success" && !res.data.reason) {
        this.redirectSessionFinished = lib.verifySessionFinished(
          res.status,
          200
        );
        this.updateAlert(true, "Registro eliminado.", "success");
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
      if (this.$v.$invalid) {
        this.updateAlert(true, "Campos obligatorios.", "fail");

        return;
      }

      if (this.editedIndex > -1) {
        const res = await trackingCuscaApi
          .put(`/${this.editedItem.id}`, this.editedItem)
          .catch((error) => {
            this.updateAlert(
              true,
              "No fue posible modificar el registro.",
              "fail"
            );
            this.close();
            this.redirectSessionFinished = lib.verifySessionFinished(
              error.response.status,
              419
            );
          });

        if (res.data.reason) {
          this.updateAlert(true, res.data.reason, "fail");
        }

        if (res.data.message == "success" && !res.data.reason) {
          this.updateAlert(
            true,
            "Registro actualizado correctamente.",
            "success"
          );
        }
      } else {
        const res = await trackingCuscaApi
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
          for (let i = 0; i < record.tracking_detail.length; i++) {
            searchConcat += record.tracking_detail[i].toUpperCase();
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
      // this.editedItem.user_name = users[0].user_name;
      this.editedItem.month_name = this.months[0].month_name;
      this.editedItem.value = this.years[0].value;
      this.editedItem.status_name = this.trakingStatuses[0].status_name;
      this.editedItem.action_description = this.actions[0].action_description;
      this.editedItem.observation = this.observations[0].observation;
      this.editedItem.tracking_detail = "";
      this.editedItem.budget_executed = 0;
      this.editedItem.monthly_actions = 0;
      this.editedItem.executed = false;
    },

    async filterTracking(filter ="Mensuales"){
      const response = await trackingCuscaApi.get(null, {
        params: {
          filter: filter
        }
      })
      .catch((error) => {
        this.updateAlert(
          true,
          "No fue posible obtener los registro del filtro.",
          "fail"
        );
        this.redirectSessionFinished = lib.verifySessionFinished(
          error.response.status,
          419
        );
      });

      this.records = response.data.trackingsCusca;
      this.recordsFiltered = this.records;
    },
  },
};
</script>

