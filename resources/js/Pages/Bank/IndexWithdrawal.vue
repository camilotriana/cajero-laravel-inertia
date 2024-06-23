<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { Head, useForm } from "@inertiajs/vue3";
import Swal from "sweetalert2";

const props = defineProps({
    saldoU: String,
    validate: Boolean,
    mensaje: String,
    billetes: String
});

const form = useForm({
    cantidad: null,
});

function sumbitRetirar() {
    if (form.cantidad >= 1000 && form.cantidad <= 2000000) {
        form.post(route("bank.storeWithdrawal"), {
            onSuccess: (e) => {
                if (props.validate) {
                    Swal.fire({
                        title: props.mensaje,
                        html: props.billetes,
                        icon: "success",
                        confirmButtonText: "Aceptar",
                        confirmButtonColor: "#198754",
                    });
                } else {
                    Swal.fire({
                        title: props.mensaje,
                        icon: "error",
                        confirmButtonText: "Aceptar",
                        confirmButtonColor: "#dc3545",
                    });
                }

                form.cantidad = null;
            },
        });
    } else {
        Swal.fire({
            title: "La cantidad ingresada no es valida",
            icon: "warning",
            confirmButtonText: "Aceptar",
            confirmButtonColor: "#dc3545",
        });
    }
}
</script>

<template>
    <Head title="Consignar" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Retirar
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div
                    class="w-full md:w-1/2 mx-auto bg-white overflow-hidden shadow-sm sm:rounded-lg"
                >
                    <div class="w-full py-3">
                        <h3 class="text-center text-2xl text-gray-700">
                            Saldo: ${{ props.saldoU }}
                        </h3>
                        <hr class="mt-3" />
                    </div>
                    <div class="flex flex-wrap px-4 py-6">
                        <div class="w-full">
                            <InputLabel for="cantidad" value="Cantidad" />
                            <input
                                id="cantidad"
                                type="number"
                                v-model="form.cantidad"
                                class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                autofocus
                                placeholder="Ingrese la cantidad"
                            />
                        </div>
                        <div class="w-full flex justify-center">
                            <PrimaryButton
                                class="mt-3"
                                @click="sumbitRetirar()"
                            >
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20"
                                    fill="currentColor"
                                    class="size-4 mr-1"
                                >
                                    <path
                                        d="M10.75 10.818v2.614A3.13 3.13 0 0 0 11.888 13c.482-.315.612-.648.612-.875 0-.227-.13-.56-.612-.875a3.13 3.13 0 0 0-1.138-.432ZM8.33 8.62c.053.055.115.11.184.164.208.16.46.284.736.363V6.603a2.45 2.45 0 0 0-.35.13c-.14.065-.27.143-.386.233-.377.292-.514.627-.514.909 0 .184.058.39.202.592.037.051.08.102.128.152Z"
                                    />
                                    <path
                                        fill-rule="evenodd"
                                        d="M18 10a8 8 0 1 1-16 0 8 8 0 0 1 16 0Zm-8-6a.75.75 0 0 1 .75.75v.316a3.78 3.78 0 0 1 1.653.713c.426.33.744.74.925 1.2a.75.75 0 0 1-1.395.55 1.35 1.35 0 0 0-.447-.563 2.187 2.187 0 0 0-.736-.363V9.3c.698.093 1.383.32 1.959.696.787.514 1.29 1.27 1.29 2.13 0 .86-.504 1.616-1.29 2.13-.576.377-1.261.603-1.96.696v.299a.75.75 0 1 1-1.5 0v-.3c-.697-.092-1.382-.318-1.958-.695-.482-.315-.857-.717-1.078-1.188a.75.75 0 1 1 1.359-.636c.08.173.245.376.54.569.313.205.706.353 1.138.432v-2.748a3.782 3.782 0 0 1-1.653-.713C6.9 9.433 6.5 8.681 6.5 7.875c0-.805.4-1.558 1.097-2.096a3.78 3.78 0 0 1 1.653-.713V4.75A.75.75 0 0 1 10 4Z"
                                        clip-rule="evenodd"
                                    />
                                </svg>

                                Retirar
                            </PrimaryButton>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
