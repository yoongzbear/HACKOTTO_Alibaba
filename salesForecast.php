<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Selector</title>
    <link rel="stylesheet" href="style.css" />

    <style>
    .dashboard {
      min-height: auto;
      padding: 2rem;
      max-width: 90%;
      margin: 2rem auto;
      /* ← Added vertical margin */
      background-color: #ffffff;
      border-radius: 8px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }

    #timelineSelect{
        width: auto;
        max-width: 300px;
        display: inline-block;
        margin-top: 0.3rem;
    }

    #generateButton {
        width: auto;
        max-width: 200px;
        display: inline-block;
        margin-top: 0.3rem;
    }

    /* Optional: Align dropdown and button on same line */
    .label-input-group {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        align-items: center;
        margin-bottom: 1.5rem;
    }

    .label-input-group label {
        flex: 1 1 100%;
        margin-bottom: 0.3rem;
    }

    .label-input-group select,
    .label-input-group button {
        flex: 1;
        min-width: 150px;
    }

    .text-button {
        background-color: #007bff;
        color: white;
        border: none;
        padding: 0.6rem 1.2rem;
        font-size: 1rem;
        font-weight: 500;
        border-radius: 6px;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 2px 6px rgba(0, 123, 255, 0.2);
    }

    .text-button:hover {
        background-color: #0056b3;
        box-shadow: 0 4px 10px rgba(0, 123, 255, 0.3);
        transform: translateY(-1px);
    }

    .text-button:active {
        transform: translateY(0);
    }

    .spinner {
        display: inline-block;
        width: 32px;
        height: 32px;
        border: 4px solid #007bff;
        border-radius: 50%;
        border-top-color: transparent;
        animation: spin 0.8s linear infinite;
        box-sizing: border-box;
    }

    @keyframes spin {
        to { transform: rotate(360deg); }
    }

    </style>
  
</head>

<body>
    <div class="dashboard">

    <!-- Navigation -->
    <div class="back-bar">
      <a href="index.html" class="back-button">← Back to Dashboard</a>
    </div>

    <div class="label-input-group">
        <!-- Selection Controls -->
        <label for="timelineSelect">Select Timeline:</label>
            <div class="input-with-button">
                <select id="timelineSelect">
                <option value="">--Select--</option>
                <option value="1day">Next Day</option>
                <option value="7days">Next Week</option>
                <option value="30days">Next Month</option>
            </select>
            <button id="generateButton" class="text-button" title="Generate">Generate</button>
        </div>
        
    </div>

    <!-- Inline Loading Message -->
    <div id="loadingMessage" style="display:none; margin-top:1rem; text-align:center;">
        <div class="spinner"></div>
        <p style="margin-top: 0.5rem; font-size:1rem;">Generating...</p>
    </div>

 <!-- Chart & Table Sections -->
    <div id="sections">

        <!-- 1 Day -->
        <div id="section-1day" class="timeline-section" style="display:none;">
            <h2>Next Day - Forecast Table</h2>
            <iframe src="https://bi-ap-southeast-3.data.aliyun.com/token3rd/dashboard/view/pc.htm?pageId=47102060-be08-4e6e-ba4c-1c7e80459779&accessTicket=35f8fda2-9e99-435b-9b6c-99f4a61b5484&dd_orientation=auto" width="100%" height="400" frameborder="0"></iframe>
            <h2>Next Day - Forecast Chart</h2>
            <iframe src="https://bi-ap-southeast-3.data.aliyun.com/token3rd/dashboard/view/pc.htm?pageId=bf571edf-367e-4728-8b4f-d2d154409ad0&accessTicket=56481aac-e45a-416f-9f55-8b62981a1689&dd_orientation=auto" width="100%" height="500" frameborder="0"></iframe>
        </div>

        <!-- 7 Days -->
        <div id="section-7days" class="timeline-section" style="display:none;">
            <h2>Next Week - Forecast Table</h2>
            <iframe src="https://bi-ap-southeast-3.data.aliyun.com/token3rd/dashboard/view/pc.htm?pageId=bc8853af-f40c-4bd4-96da-a9d6f35c84f1&accessTicket=0b23825e-9695-4754-a1c8-93ac775f76c1&dd_orientation=auto" width="100%" height="400" frameborder="0"></iframe>
            <h2>Next Week - Forecast Chart</h2>
            <iframe src="https://bi-ap-southeast-3.data.aliyun.com/token3rd/dashboard/view/pc.htm?pageId=c269ba42-8b17-4528-bf74-6e332e6b5b95&accessTicket=267078dc-94df-4444-a91f-0ba1b3f666e2&dd_orientation=auto" width="100%" height="500" frameborder="0"></iframe>
        </div>

        <!-- 30 Days -->
        <div id="section-30days" class="timeline-section" style="display:none;">
            <h2>Next Month - Forecast Table</h2>
            <iframe src="https://bi-ap-southeast-3.data.aliyun.com/token3rd/dashboard/view/pc.htm?pageId=f5ffcbbd-76ac-4e87-8136-84c9f067d4d6&accessTicket=40cd3b2c-c399-4ebd-b7df-8fd680fa51ef&dd_orientation=auto" width="100%" height="400" frameborder="0"></iframe>
            <h2>Next Month - Forecast Chart</h2>
            <iframe src="https://bi-ap-southeast-3.data.aliyun.com/token3rd/dashboard/view/pc.htm?pageId=0cc4aabe-d8a2-458a-8ae0-1676e2797519&accessTicket=5b6d6a61-1195-4370-919c-9cde48ae09e3&dd_orientation=auto" width="100%" height="500" frameborder="0"></iframe>
        </div>

    </div>
</div>

<script>
    const dropdown = document.getElementById("timelineSelect");
    const generateButton = document.getElementById("generateButton");
    const loadingMessage = document.getElementById("loadingMessage");
    const sections = document.querySelectorAll(".timeline-section");

    generateButton.addEventListener("click", function () {
        const selectedTimeline = dropdown.value;
        if (!selectedTimeline) return;

        // Start loading
        generateButton.disabled = true;
        loadingMessage.style.display = "block";

        // Hide all sections first
        sections.forEach(section => section.style.display = "none");

        // Simulate delay
        setTimeout(() => {
            // Show selected section
            const sectionToShow = document.getElementById(`section-${selectedTimeline}`);
            if (sectionToShow) sectionToShow.style.display = "block";

            // End loading
            loadingMessage.style.display = "none";
            generateButton.disabled = false;

            // Optional: Update URL
            window.history.pushState(null, '', `?timeline=${encodeURIComponent(selectedTimeline)}`);
        }, 1500);
    });
    </script>

</body>
</html>
