<script setup>
import { ref, onMounted, onBeforeUnmount } from 'vue';
import { useForm, Head } from "@inertiajs/vue3";
import { toast } from "vue3-toastify";
import 'vue3-toastify/dist/index.css';

const form = useForm({
    name: '',
    email: '',
    message: '',
});

const errorMessage = ref('');
const activeSection = ref('home')
const message = "Johnny Ermio";
const typingSpeed = 100;
const displayedText = ref("");
const isCursorVisible = ref(true);
const sidebar = ref(false);

function submit() {
    form.post(route('feedback.store'), {
        preserveScroll: true,
        onSuccess: () => {
            toast("Thank you for sending feedback!", {
                "type": "success",
                "position": "bottom-right",
                "autoClose": 2000,
                "hideProgressBar": true,
                "transition": "bounce",
                "dangerouslyHTMLString": true,
            })
            form.reset();
        },
        onError: (errors) => {
            if (errors) {
                errorMessage.value = 'There was an error submitting the form. Please check the fields and try again.';
            }
        },
        onFinish: () => {
            if (!form.hasErrors) {
                errorMessage.value = '';
            }
        },
    });
}

let currentIndex = 0;
function type() {
    if (currentIndex < message.length) {
        displayedText.value += message[currentIndex];
        currentIndex++;
        setTimeout(type, typingSpeed);
    }
}

function blinkCursor() {
    setInterval(() => {
        isCursorVisible.value = !isCursorVisible.value;
    }, 500); // Cursor blink speed
}

const scrollToSection = (sectionId) => {
    sidebar.value = false;
    const section = document.getElementById(sectionId)
    if (section) {
        section.scrollIntoView({ behavior: 'smooth' })
    }
}

const observeSections = () => {
    const sections = document.querySelectorAll('section');
    const observerOptions = { threshold: 0.5 };

    const observer = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    activeSection.value = entry.target.id;

                    // Add the 'active' class to animate elements within the visible section
                    const fadeInElements = entry.target.querySelectorAll('.fade-in');
                    const slideInLeftElements = entry.target.querySelectorAll('.slide-in-left');
                    const slideInTopElements = entry.target.querySelectorAll('.slide-in-top');
                    const slideInBottomElements = entry.target.querySelectorAll('.slide-in-bottom');

                    fadeInElements.forEach((el) => el.classList.add('active'));
                    slideInLeftElements.forEach((el) => el.classList.add('active'));
                    slideInTopElements.forEach((el) => el.classList.add('active'));
                    slideInBottomElements.forEach((el) => el.classList.add('active'));
                } else {
                    // Remove the 'active' class when the section goes out of view
                    const fadeInElements = entry.target.querySelectorAll('.fade-in');
                    const slideInLeftElements = entry.target.querySelectorAll('.slide-in-left');
                    const slideInTopElements = entry.target.querySelectorAll('.slide-in-top');
                    const slideInBottomElements = entry.target.querySelectorAll('.slide-in-bottom');

                    fadeInElements.forEach((el) => el.classList.remove('active'));
                    slideInLeftElements.forEach((el) => el.classList.remove('active'));
                    slideInTopElements.forEach((el) => el.classList.remove('active'));
                    slideInBottomElements.forEach((el) => el.classList.remove('active'));
                }
            });
        },
        observerOptions
    );

    sections.forEach((section) => observer.observe(section));

    onBeforeUnmount(() => {
        sections.forEach((section) => observer.unobserve(section));
    });
};


const linkClass = (sectionId) => {
    return activeSection.value === sectionId
        ? 'text-teal-500 font-semibold border-b-2 border-teal-500'
        : 'text-white hover:text-teal-500 transition duration-300'
}

const sideNavlinkClass = (sectionId) => {
    return activeSection.value === sectionId
        ? 'flex border-y-2 border-teal-500 rounded-lg bg-teal-500 px-2 py-1 font-semibold border border-teal-500 rounded'
        : 'flex border-y-2 border-teal-500 rounded-lg text-white px-2 py-1 hover:bg-teal-500 transition duration-300 rounded'
}

onMounted(() => {
    observeSections();
    type();
    blinkCursor();
})
</script>

<template>

    <Head title="Welcome" />
    <div>
        <nav :class="activeSection !== 'home' ? ' bg-gray-900 shadow-lg' : 'bg-transparent'"
            class="fixed top-0 w-full flex items-center justify-between px-8 py-2 md:py-4 z-10">
            <div class="flex space-x-2 items-center">
                <div @click="sidebar = true"
                    class="sm:hidden flex cursor-pointer hover:bg-teal-600 my-2 rounded-md border border-teal-500 shadow-md">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="white" class="size-8">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                    </svg>
                </div>
                <div class="hidden text-teal-500 text-xl font-bold sm:flex">
                    PORTFOLIO.
                </div>
            </div>

            <div class="hidden cursor-pointer sm:flex space-x-6">
                <a :class="linkClass('home')" @click.prevent="scrollToSection('home')">Home</a>
                <a :class="linkClass('about')" @click.prevent="scrollToSection('about')">About</a>
                <a :class="linkClass('services')" @click.prevent="scrollToSection('services')">Services</a>
                <a :class="linkClass('portfolio')" @click.prevent="scrollToSection('portfolio')">Portfolio</a>
                <a :class="linkClass('contact')" @click.prevent="scrollToSection('contact')">Contact</a>
            </div>
        </nav>

        <Transition name="sidebar">
            <div v-if="sidebar"
                class="fixed sm:hidden top-0 left-0 z-40 w-64 shadow-sm shadow-teal-500 h-screen p-2 overflow-y-auto bg-gray-950">
                <div class="flex py-2 px-2 items-center justify-between flex-shrink-0">
                    <span
                        class="flex items-center flex-nowrap space-x-2 text-xl leading-8 tracking-wider uppercase whitespace-nowrap">
                        <span class="antialiased tracking-wider font-black leading-none text-white">PORTFOLIO.</span>
                    </span>
                    <button @click="sidebar = false" class="p-2 rounded-md lg:hidden">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="white" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                        </svg>

                    </button>
                </div>

                <div class="px-2 overflow-y-auto">
                    <ul class="space-y-4 font-medium tracking-wider cursor-pointer text-white mb-6">
                        <li>
                            <a :class="sideNavlinkClass('home')" @click.prevent="scrollToSection('home')">Home</a>
                        </li>
                        <li>
                            <a :class="sideNavlinkClass('about')" @click.prevent="scrollToSection('about')">About</a>
                        </li>
                        <li>
                            <a :class="sideNavlinkClass('services')"
                                @click.prevent="scrollToSection('services')">Services</a>
                        </li>
                        <li>
                            <a :class="sideNavlinkClass('portfolio')"
                                @click.prevent="scrollToSection('portfolio')">Portfolio</a>
                        </li>
                        <li>
                            <a :class="sideNavlinkClass('contact')"
                                @click.prevent="scrollToSection('contact')">Contact</a>
                        </li>
                    </ul>
                </div>
            </div>
        </Transition>

        <section id="home"
            class="h-screen flex flex-col md:flex-row items-center bg-auto bg-left bg-no-repeat bg-gray-950 lg:bg-[url('/images/bg.png')] px-8 lg:bg-cover">
            <div
                class="flex flex-col md:flex-1 space-y-4 max-w-screen-xl md:space-y-6 text-center md:text-left mt-24 lg:mt-0">
                <h1 class="text-3xl md:text-4xl font-bold text-white">
                    Hello, <span class="text-teal-500">I am</span>
                </h1>

                <div class="typing-container flex items-center">
                    <span class="text text-2xl md:text-4xl font-bold text-white">{{ displayedText }}</span>
                    <span v-if="isCursorVisible" class="cursor text-2xl md:text-4xl font-bold text-white">|</span>
                </div>

                <div class="flex justify-center md:justify-start space-x-2">
                    <a href="https://web.facebook.com/nyjaaaaa" target="_blank"
                        class="text-white rounded-full px-2 py-2 border border-teal-500 shadow-lg shadow-teal-500 hover:bg-teal-500">

                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="feather feather-facebook">
                            <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path>
                        </svg>
                    </a>
                    <a href="https://www.linkedin.com/in/johnny-ermio-600333329" target="_blank"
                        class="text-white rounded-full px-2 py-2 border border-teal-500 shadow-lg shadow-teal-500 hover:bg-teal-500">

                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="feather feather-linkedin">
                            <path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z">
                            </path>
                            <rect x="2" y="9" width="4" height="12"></rect>
                            <circle cx="4" cy="4" r="2"></circle>
                        </svg>
                    </a>
                    <a href="https://github.com/ny-ja" target="_blank"
                        class="text-white rounded-full px-2 py-2 border border-teal-500 shadow-lg shadow-teal-500 hover:bg-teal-500">

                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="feather feather-github">
                            <path
                                d="M9 19c-5 1.5-5-2.5-7-3m14 6v-3.87a3.37 3.37 0 0 0-.94-2.61c3.14-.35 6.44-1.54 6.44-7A5.44 5.44 0 0 0 20 4.77 5.07 5.07 0 0 0 19.91 1S18.73.65 16 2.48a13.38 13.38 0 0 0-7 0C6.27.65 5.09 1 5.09 1A5.07 5.07 0 0 0 5 4.77a5.44 5.44 0 0 0-1.5 3.78c0 5.42 3.3 6.61 6.44 7A3.37 3.37 0 0 0 9 18.13V22">
                            </path>
                        </svg>
                    </a>
                </div>
                <a href="/documents/cv.pdf" download
                    class="inline-block bg-transparent text-white text-center border-y-2 shadow-lg shadow-teal-500 border-teal-500 py-2 px-4 rounded-lg w-40 hover:bg-teal-600 transition">
                    Download CV
                </a>
            </div>
        </section>

        <section id="about" class="h-screen flex bg-gray-950 px-8">
            <div class="container px-5 py-24 mx-auto">
                <div class="lg:w-4/5 mx-auto flex flex-nowrap">
                    <img alt="about-photo"
                        class="lg:w-[60vh] w-[30vh] lg:h-auto object-cover object-center rounded-lg border-x-2 border-teal-500 shadow-lg shadow-teal-500 fade-in"
                        src="/images/about-photo.jpeg">
                    <div class="lg:w-1/2 w-full pl-10 lg:py-6 mt-6 lg:mt-0 slide-in-left">
                        <h1
                            class="text-teal-500 border-b border-teal-500 md:text-3xl title-font font-medium mb-1 text-xs">
                            About
                        </h1>
                        <p class="leading-relaxed text-gray-200 text-justify md:text-xl text-xs">
                            I am a passionate and highly skilled Full Stack Web Developer with extensive experience in
                            building and
                            maintaining web applications using Laravel, Inertia.js, and Vue.js. I excel at creating
                            dynamic,
                            user-friendly, and scalable web solutions. With a deep understanding of both front-end and
                            back-end
                            technologies, I am adept at tackling complex problems, optimizing performance, and
                            implementing elegant
                            design solutions.
                        </p>
                        <a href="https://www.linkedin.com/in/johnny-ermio-600333329/" target="_blank"
                            class="inline-block bg-transparent border-y-2 border-teal-500 shadow-lg shadow-teal-500 text-white mt-4 text-center py-1 px-1 md:py-2 md:px-4 sm:text-lg text-xs rounded-lg hover:bg-teal-600 transition">
                            Read more
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <section id="services" class="h-screen bg-gray-950 px-8">
            <div class="container px-5 py-24 mx-auto">
                <div class="flex flex-col text-center w-full mb-10 slide-in-top">
                    <h1 class="sm:text-3xl text-2xl font-medium title-font text-gray-200">MY SERVICES</h1>
                    <h2 class="text-xs text-teal-500 tracking-widest font-medium title-font mb-1">Your Go-To Solutions
                        for Every Challenge</h2>
                </div>
                <div class="flex flex-wrap slide-in-bottom">
                    <div class="p-4 md:w-1/3">
                        <div
                            class="flex rounded-lg h-full bg-gray-100 p-2 md:p-6 flex-col border-x-2 border-teal-500 shadow-lg shadow-teal-500">
                            <div class="flex items-center mb-3">
                                <div
                                    class="w-4 h-4 md:w-8 md:h-8 mr-3 inline-flex items-center justify-center rounded-full bg-teal-500 text-white flex-shrink-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="size-6">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M17.25 6.75 22.5 12l-5.25 5.25m-10.5 0L1.5 12l5.25-5.25m7.5-3-4.5 16.5" />
                                    </svg>

                                </div>
                                <h2 class="text-gray-900 md:text-lg text-sm title-font font-medium">Web Development</h2>
                            </div>
                            <div class="flex-grow">
                                <p class="leading-relaxed md:text-base text-xs">Custom websites and web applications to
                                    bring your
                                    business online with modern, responsive designs and functionality tailored to your
                                    needs.</p>

                            </div>
                        </div>
                    </div>

                    <div class="p-4 md:w-1/3">
                        <div
                            class="flex rounded-lg h-full bg-gray-100 p-2 md:p-6 flex-col border-x-2 border-teal-500 shadow-lg shadow-teal-500">
                            <div class="flex items-center mb-3">
                                <div
                                    class="w-4 h-4 md:w-8 md:h-8 mr-3 inline-flex items-center justify-center rounded-full bg-teal-500 text-white flex-shrink-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="size-6">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M12 18v-5.25m0 0a6.01 6.01 0 0 0 1.5-.189m-1.5.189a6.01 6.01 0 0 1-1.5-.189m3.75 7.478a12.06 12.06 0 0 1-4.5 0m3.75 2.383a14.406 14.406 0 0 1-3 0M14.25 18v-.192c0-.983.658-1.823 1.508-2.316a7.5 7.5 0 1 0-7.517 0c.85.493 1.509 1.333 1.509 2.316V18" />
                                    </svg>

                                </div>
                                <h2 class="text-gray-900 md:text-lg text-sm title-font font-medium">Capstone Project
                                    Assistance
                                </h2>
                            </div>
                            <div class="flex-grow">
                                <p class="leading-relaxed md:text-base text-xs">Comprehensive support for capstone
                                    projects, from
                                    ideation to implementation, providing technical guidance, resources, and project
                                    management support.</p>

                            </div>
                        </div>
                    </div>

                    <div class="p-4 md:w-1/3">
                        <div
                            class="flex rounded-lg h-full bg-gray-100 p-2 md:p-6 flex-col border-x-2 border-teal-500 shadow-lg shadow-teal-500">
                            <div class="flex items-center mb-3">
                                <div
                                    class="w-4 h-4 md:w-8 md:h-8 mr-3 inline-flex items-center justify-center rounded-full bg-teal-500 text-white flex-shrink-0">

                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="size-6">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M8.25 3v1.5M4.5 8.25H3m18 0h-1.5M4.5 12H3m18 0h-1.5m-15 3.75H3m18 0h-1.5M8.25 19.5V21M12 3v1.5m0 15V21m3.75-18v1.5m0 15V21m-9-1.5h10.5a2.25 2.25 0 0 0 2.25-2.25V6.75a2.25 2.25 0 0 0-2.25-2.25H6.75A2.25 2.25 0 0 0 4.5 6.75v10.5a2.25 2.25 0 0 0 2.25 2.25Zm.75-12h9v9h-9v-9Z" />
                                    </svg>

                                </div>
                                <h2 class="text-gray-900 md:text-lg text-sm title-font font-medium">Computer Systems
                                    Servicing</h2>
                            </div>
                            <div class="flex-grow">
                                <p class="leading-relaxed md:text-base text-xs">Professional servicing and
                                    troubleshooting for
                                    computer systems, including hardware and software installations, upgrades, and
                                    maintenance.</p>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="portfolio" class="flex items-center justify-center bg-gray-950 px-8">
            <div class="container px-5 py-24 mx-auto">
                <div class="flex flex-wrap">
                    <div class="flex flex-col text-center w-full mb-10 slide-in-top">
                        <h1 class="sm:text-3xl text-2xl font-medium title-font text-gray-200">LATEST PROJECTS</h1>
                        <h2 class="text-xs text-teal-500 tracking-widest font-medium title-font mb-1">A Showcase of My
                            Most Recent Work and Innovations</h2>
                    </div>
                    <div class="p-4 md:w-1/3 slide-in-bottom">
                        <div
                            class="h-full border-x-2 border-teal-500 shadow-lg shadow-teal-500 border-opacity-60 rounded-lg overflow-hidden">
                            <img class="lg:h-48 md:h-36 w-full object-cover object-center hidden sm:flex"
                                src="/images/prims.png" alt="prims-photo">
                            <div class="p-2">
                                <div class="flex justify-between items-center">
                                    <h2 class="tracking-widest text-xs title-font font-medium text-gray-200 mb-1">WEBAPP
                                    </h2>
                                    <div class="flex items-center flex-wrap">
                                        <a href="https://github.com/ny-ja/prims" target="_blank"
                                            class="text-white rounded-full px-1 py-1 border border-teal-500 shadow-lg shadow-teal-500 hover:bg-teal-500">

                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round"
                                                class="feather feather-github">
                                                <path
                                                    d="M9 19c-5 1.5-5-2.5-7-3m14 6v-3.87a3.37 3.37 0 0 0-.94-2.61c3.14-.35 6.44-1.54 6.44-7A5.44 5.44 0 0 0 20 4.77 5.07 5.07 0 0 0 19.91 1S18.73.65 16 2.48a13.38 13.38 0 0 0-7 0C6.27.65 5.09 1 5.09 1A5.07 5.07 0 0 0 5 4.77a5.44 5.44 0 0 0-1.5 3.78c0 5.42 3.3 6.61 6.44 7A3.37 3.37 0 0 0 9 18.13V22">
                                                </path>
                                            </svg>
                                        </a>
                                    </div>
                                </div>

                                <h1 class="title-font text-lg font-medium text-teal-500 mb-3">Purok Resident Information
                                    Management System (PRIMS)</h1>
                                <p class="leading-relaxed text-gray-200 mb-3">A web application designed to streamline
                                    and manage resident information within a purok efficiently.</p>

                            </div>
                        </div>
                    </div>
                    <div class="p-4 md:w-1/3 slide-in-bottom">
                        <div
                            class="h-full border-x-2 border-teal-500 shadow-lg shadow-teal-500 border-opacity-60 rounded-lg overflow-hidden">
                            <img class="lg:h-48 md:h-36 w-full object-cover object-center hidden sm:flex"
                                src="/images/poblacion.png" alt="poblacion-website">
                            <div class="p-2">
                                <div class="flex justify-between items-center">
                                    <h2 class="tracking-widest text-xs title-font font-medium text-gray-200 mb-1">WEBAPP
                                    </h2>
                                    <div class="flex items-center flex-wrap">
                                        <a href="https://github.com/ny-ja/barangay-poblacion" target="_blank"
                                            class="text-white rounded-full px-1 py-1 border border-teal-500 shadow-lg shadow-teal-500 hover:bg-teal-500">

                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round"
                                                class="feather feather-github">
                                                <path
                                                    d="M9 19c-5 1.5-5-2.5-7-3m14 6v-3.87a3.37 3.37 0 0 0-.94-2.61c3.14-.35 6.44-1.54 6.44-7A5.44 5.44 0 0 0 20 4.77 5.07 5.07 0 0 0 19.91 1S18.73.65 16 2.48a13.38 13.38 0 0 0-7 0C6.27.65 5.09 1 5.09 1A5.07 5.07 0 0 0 5 4.77a5.44 5.44 0 0 0-1.5 3.78c0 5.42 3.3 6.61 6.44 7A3.37 3.37 0 0 0 9 18.13V22">
                                                </path>
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                                <h1 class="title-font text-lg font-medium text-teal-500 mb-3">Barangay Poblacion Website
                                </h1>
                                <p class="leading-relaxed text-gray-200 mb-3">An online hub for Barangay Poblacion,
                                    offering residents quick access to news, events, announcements, and essential public
                                    services.</p>
                            </div>
                        </div>
                    </div>
                    <div class="p-4 md:w-1/3 slide-in-bottom">
                        <div
                            class="h-full border-x-2 border-teal-500 shadow-lg shadow-teal-500 border-opacity-60 rounded-lg overflow-hidden">
                            <img class="lg:h-48 md:h-36 w-full object-cover object-center hidden sm:flex"
                                src="/images/summit-guardian.png" alt="summit guardian">
                            <div class="p-2">
                                <div class="flex justify-between items-center">
                                    <h2 class="tracking-widest text-xs title-font font-medium text-gray-200 mb-1">
                                        CAPSTONE
                                    </h2>
                                    <div class="flex items-center flex-wrap">
                                        <a href="https://github.com/ny-ja/summit-guardian" target="_blank"
                                            class="text-white rounded-full px-1 py-1 border border-teal-500 shadow-lg shadow-teal-500 hover:bg-teal-500">

                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round"
                                                class="feather feather-github">
                                                <path
                                                    d="M9 19c-5 1.5-5-2.5-7-3m14 6v-3.87a3.37 3.37 0 0 0-.94-2.61c3.14-.35 6.44-1.54 6.44-7A5.44 5.44 0 0 0 20 4.77 5.07 5.07 0 0 0 19.91 1S18.73.65 16 2.48a13.38 13.38 0 0 0-7 0C6.27.65 5.09 1 5.09 1A5.07 5.07 0 0 0 5 4.77a5.44 5.44 0 0 0-1.5 3.78c0 5.42 3.3 6.61 6.44 7A3.37 3.37 0 0 0 9 18.13V22">
                                                </path>
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                                <h1 class="title-font text-lg font-medium text-teal-500 mb-3">Summit Guardian</h1>
                                <p class="leading-relaxed text-gray-200 mb-3">A Comprehensive IoT Solution for Mountain
                                    Climber Health Monitoring and GPS Tracking in Search and Rescue Operations.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="contact" class="flex bg-gray-950 px-8">
            <div class="container px-5 py-24 mx-auto flex sm:flex-nowrap flex-wrap">
                <div
                    class="lg:w-2/3 md:w-1/2 bg-transparent border-x-2 shadow-lg shadow-teal-500 border-teal-500 rounded-lg overflow-hidden sm:mr-10 p-10 flex items-end justify-start relative fade-in">
                    <iframe width="100%" height="100%" class="absolute inset-0" frameborder="0" title="map"
                        marginheight="0" marginwidth="0" scrolling="no"
                        src="https://maps.google.com/maps?width=100%&amp;height=600&amp;hl=en&amp;q=7.917329, 125.184625&amp;ie=UTF8&amp;t=&amp;z=14&amp;iwloc=B&amp;output=embed"
                        style="filter: grayscale(1) contrast(1.2) opacity(0.4);"></iframe>
                    <div class="bg-white relative flex flex-wrap py-6 rounded shadow-md">
                        <div class="lg:w-1/2 px-6">
                            <h2 class="title-font font-semibold text-gray-900 tracking-widest text-xs">ADDRESS</h2>
                            <p class="mt-1">Poblacion, Valencia City, Bukidnon, Philippines</p>
                        </div>
                        <div class="lg:w-1/2 px-6 mt-4 lg:mt-0">
                            <h2 class="title-font font-semibold text-gray-900 tracking-widest text-xs">EMAIL</h2>
                            <a class="text-teal-500 leading-relaxed">p.ermio.johnny@gmail.com</a>
                            <h2 class="title-font font-semibold text-gray-900 tracking-widest text-xs mt-4">PHONE</h2>
                            <p class="leading-relaxed">+63 912-637-8712</p>
                        </div>
                    </div>
                </div>
                <div
                    class="p-6 lg:w-1/3 md:w-1/2 bg-transparent border-x-2 shadow-lg shadow-teal-500 border-teal-500 flex flex-col md:ml-auto w-full md:py-8 mt-8 md:mt-0 rounded-lg slide-in-left">
                    <h2 class="text-teal-500 text-center text-lg mb-1 font-medium title-font">Feedback</h2>
                    <form @submit.prevent="submit">
                        <div v-if="errorMessage" class="text-red-500 text-sm">
                            {{ errorMessage }}
                        </div>
                        <div class="relative mb-4">
                            <label for="name" class="leading-7 text-sm text-gray-400">Name</label>
                            <input type="text" v-model="form.name" id="name" name="name"
                                class="w-full bg-transparent rounded border border-teal-500 focus:border-teal-500 focus:ring-1 focus:ring-teal-500 text-base outline-none text-gray-400 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                            <div v-if="form.errors.name" class="text-red-500 text-sm mt-1">
                                {{ form.errors.name }}
                            </div>
                        </div>
                        <div class="relative mb-4">
                            <label for="email" class="leading-7 text-sm text-gray-400">Email</label>
                            <input type="email" v-model="form.email" id="email" name="email"
                                class="w-full bg-transparent rounded border border-teal-500 focus:border-teal-500 focus:ring-1 focus:ring-teal-500 text-base outline-none text-gray-400 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                            <div v-if="form.errors.email" class="text-red-500 text-sm mt-1">
                                {{ form.errors.email }}
                            </div>
                        </div>
                        <div class="relative mb-4">
                            <label for="message" class="leading-7 text-sm text-gray-400">Message</label>
                            <textarea v-model="form.message" id="message" name="message"
                                class="w-full bg-transparent rounded border border-teal-500 focus:border-teal-500 focus:ring-1 focus:ring-teal-500 h-32 text-base outline-none text-gray-400 py-1 px-3 resize-none leading-6 transition-colors duration-200 ease-in-out"></textarea>
                            <div v-if="form.errors.message" class="text-red-500 text-sm mt-1">
                                {{ form.errors.message }}
                            </div>
                        </div>
                        <button type="submit"
                            class="text-white w-full mt-4 bg-transparent border-y-2 border-teal-500 shadow-lg shadow-teal-500 py-2 px-6 focus:outline-none hover:bg-teal-700 rounded-lg text-lg">Submit</button>
                    </form>
                </div>
            </div>
        </section>
    </div>
</template>

<style scoped>
.sidebar-enter-active,
.sidebar-leave-active {
    transition: opacity 0.3s, transform 0.3s;
}

.sidebar-enter-from,
.sidebar-leave-to {
    opacity: 0;
    transform: translateX(-100%);
}

.sidebar-enter-to,
.sidebar-leave-from {
    opacity: 1;
    transform: translateX(0);
}

.fade-in {
    opacity: 0;
    transform: translateY(30px);
    transition: opacity 0.6s ease, transform 0.6s ease;
}

.fade-in.active {
    opacity: 1;
    transform: translateY(0);
}

.slide-in-left {
    opacity: 0;
    transform: translateX(-50px);
    transition: opacity 0.6s ease, transform 0.6s ease;
}

.slide-in-left.active {
    opacity: 1;
    transform: translateX(0);
}

.slide-in-top {
    opacity: 0;
    transform: translateY(-30px);
    transition: opacity 0.5s ease-out, transform 0.5s ease-out;
}

.slide-in-top.active {
    opacity: 1;
    transform: translateY(0);
}

.slide-in-bottom {
    opacity: 0;
    transform: translateY(30px);
    transition: opacity 0.5s ease-out, transform 0.5s ease-out;
}

.slide-in-bottom.active {
    opacity: 1;
    transform: translateY(0);
}
</style>