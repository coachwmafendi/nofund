@extends('layouts.app')

@section('title', $title)

@section('content')
    <x-page-header :title="$title" :description="$message" />

    <x-card>
        <x-empty-state
            icon="wrench"
            :title="$title . ' Coming Soon'"
            :description="$message"
        />
    </x-card>
@endsection
