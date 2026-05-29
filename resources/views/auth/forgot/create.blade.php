<x-layouts.auth>
    <x-slot name="title">
        {{ trans('auth.reset_password') }}
    </x-slot>

    <x-slot name="content">
        <div>
            <img src="{{ asset('public/img/apex-logo.png') }}" class="w-16" alt="{{ config('app.name') }}" />

            <h1 class="text-lg my-3">
                {{ trans('auth.reset_password') }}
            </h1>
        </div>

        <div :class="(form.response.success) ? 'w-full bg-green-100 text-green-600 p-3 rounded-sm font-semibold text-xs' : 'hidden'"
            v-if="form.response.success"
            v-html="form.response.message"
            v-cloak
        ></div>

        <div :class="(form.response.error) ? 'w-full bg-red-100 text-red-600 p-3 rounded-sm font-semibold text-xs' : 'hidden'"
            v-if="form.response.error"
            v-html="form.response.message"
            v-cloak
        ></div>

        <x-form id="auth" route="forgot">
            <div class="grid sm:grid-cols-6 gap-x-8 gap-y-6 items-center my-3.5 lg:h-64">
                <x-form.group.email
                    name="email"
                    label="{{ trans('general.email') }}"
                    placeholder="{{ trans('general.email') }}"
                    form-group-class="sm:col-span-6"
                    input-group-class="input-group-alternative"
                />

                <x-button
                    type="submit"
                    ::disabled="form.loading"
                    class="relative flex items-center justify-center gradient-btn px-6 py-2.5 text-base rounded-xl disabled:opacity-50 sm:col-span-6 font-semibold shadow-lg hover:shadow-xl transition-all duration-300"
                    override="class"
                    data-loading-text="{{ trans('general.loading') }}"
                >
                    <x-button.loading>
                        {{ trans('general.send') }}
                    </x-button.loading>
                </x-button>
            </div>
        </x-form>
    </x-slot>

    <x-script folder="auth" file="common" />
</x-layouts.auth>
