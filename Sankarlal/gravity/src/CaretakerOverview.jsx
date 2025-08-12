import React, { useState } from 'react';
import { useNavigate } from 'react-router-dom';
import Toast from './Toast';

// Using placeholder icons (emojis) for now. 
// You can replace these with an icon library like react-icons.
const CalendarIcon = () => <span>📅</span>;
const MailIcon = () => <span>✉️</span>;
const BellIcon = () => <span>🔔</span>;
const FullCalendarIcon = () => <span>🗓️</span>;

export default function CaretakerOverview() {
    const [showToast, setShowToast] = useState(false);
    const navigate = useNavigate();

    const handleSendReminder = () => {
        setShowToast(true);
        setTimeout(() => {
            setShowToast(false);
        }, 3000); 
    };

    return (
        <>
            <div className="ct-cards-row">
                <section className="ct-card ct-status-card">
                    <h2 className="ct-card-title">
                        <CalendarIcon /> Today's Status
                    </h2>
                    <div className="ct-status-item">
                        <div>
                            <div className="ct-status-title">Daily Medication Set</div>
                            <div className="ct-status-time">8:00 AM</div>
                        </div>
                        <span className="ct-status-badge">Pending</span>
                    </div>
                </section>
                <section className="ct-card ct-actions-card">
                    <h2 className="ct-card-title">Quick Actions</h2>
                    <button className="ct-action-btn" onClick={handleSendReminder}>
                        <MailIcon /> Send Reminder Email
                    </button>
                    <button className="ct-action-btn" onClick={() => navigate('/caretaker/notifications')}>
                        <BellIcon /> Configure Notifications
                    </button>
                    <button className="ct-action-btn" onClick={() => navigate('/caretaker/calendar')}>
                        <FullCalendarIcon /> View Full Calendar
                    </button>
                </section>
            </div>

            <section className="ct-card ct-progress-card">
                <h2 className="ct-card-title">Monthly Adherence Progress</h2>
                <div className="ct-progress-header">
                    <span>Overall Progress</span>
                    <span>85%</span>
                </div>
                <div 
                    className="ct-progress-bar-container"
                    style={{ '--taken-width': '73.3%', '--missed-width': '10%' }}
                >
                    <div className="ct-progress-bar-taken"></div>
                    <div className="ct-progress-bar-missed"></div>
                </div>
                <div className="ct-progress-legend">
                    <div className="ct-legend-item">
                        <span className="ct-legend-dot taken"></span>
                        <div><strong>22 days</strong><br />Taken</div>
                    </div>
                    <div className="ct-legend-item">
                        <span className="ct-legend-dot missed"></span>
                        <div><strong>3 days</strong><br />Missed</div>
                    </div>
                    <div className="ct-legend-item">
                        <span className="ct-legend-dot remaining"></span>
                        <div><strong>5 days</strong><br />Remaining</div>
                    </div>
                </div>
            </section>
            <Toast 
                message="Reminder email has been sent!" 
                show={showToast} 
                onClose={() => setShowToast(false)} 
            />
        </>
    );
} 