@extends('admin.layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
    <div class="grid gap-6 xl:grid-cols-4 lg:grid-cols-2">
        <article class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
            <p class="text-sm uppercase tracking-[0.24em] text-slate-500">Users</p>
            <p class="mt-4 text-3xl font-semibold text-slate-900">1,280</p>
            <p class="mt-2 text-sm text-slate-500">Active users this month</p>
        </article>

        <article class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
            <p class="text-sm uppercase tracking-[0.24em] text-slate-500">Courses</p>
            <p class="mt-4 text-3xl font-semibold text-slate-900">92</p>
            <p class="mt-2 text-sm text-slate-500">Published courses</p>
        </article>

        <article class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
            <p class="text-sm uppercase tracking-[0.24em] text-slate-500">Orders</p>
            <p class="mt-4 text-3xl font-semibold text-slate-900">420</p>
            <p class="mt-2 text-sm text-slate-500">Orders in the last 30 days</p>
        </article>

        <article class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
            <p class="text-sm uppercase tracking-[0.24em] text-slate-500">Revenue</p>
            <p class="mt-4 text-3xl font-semibold text-slate-900">$28.4K</p>
            <p class="mt-2 text-sm text-slate-500">Estimated monthly revenue</p>
        </article>
    </div>

    <section class="mt-8 rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
        <h2 class="text-lg font-semibold text-slate-900">Overview</h2>
        <p class="mt-3 text-sm leading-6 text-slate-600">Quick summary of the admin panel metrics. Use this area to show charts, recent activity, or KPI cards.</p>
    </section>
@endsection