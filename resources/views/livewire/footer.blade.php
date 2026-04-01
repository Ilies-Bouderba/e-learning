<?php

use Livewire\Component;

new class extends Component {
    //
};
?>

{{-- ========== FOOTER ========== --}}
<footer class="footer">
    <div class="container footer-inner">
        <div class="footer-brand">
            <a href="#" class="nav-logo">edu<span>me</span>x</a>
            <p>Your learning, wonderfully connected.</p>
        </div>
        <div class="footer-links">
            <div class="footer-col">
                <span class="footer-heading">Product</span>
                <a href="#">Courses</a>
                <a href="#">Exams</a>
                <a href="#">Teachers</a>
            </div>
            <div class="footer-col">
                <span class="footer-heading">Company</span>
                <a href="#">About</a>
                <a href="#">Blog</a>
                <a href="#">Careers</a>
            </div>
            <div class="footer-col">
                <span class="footer-heading">Legal</span>
                <a href="#">Privacy</a>
                <a href="#">Terms</a>
            </div>
        </div>
    </div>
    <div class="container footer-bottom">
        <span>&copy; {{ date('Y') }} Edumex. All rights reserved.</span>
    </div>
</footer>
