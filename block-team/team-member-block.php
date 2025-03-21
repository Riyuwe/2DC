const { registerBlockType } = wp.blocks;
const { RichText, MediaUpload } = wp.blockEditor;
const { Button } = wp.components;
const { useState } = wp.element;

registerBlockType('custom/team-member', {
    title: 'Team Member',
    icon: 'admin-users',
    category: 'common',
    attributes: {
        name: { type: 'string', source: 'text', selector: 'h3' },
        position: { type: 'string', source: 'text', selector: 'h4' },
        imageUrl: { type: 'string', source: 'attribute', selector: 'img', attribute: 'src' },
        description: { type: 'string', source: 'text', selector: 'p' }
    },
    
    edit: (props) => {
        const { attributes, setAttributes } = props;
        const [isVisible, setIsVisible] = useState(false);

        return (
            <div className="team-member">
                <MediaUpload
                    onSelect={(media) => setAttributes({ imageUrl: media.url })}
                    type="image"
                    render={({ open }) => (
                        <Button onClick={open} className="button button-large">
                            {attributes.imageUrl ? <img src={attributes.imageUrl} alt="Profile" /> : 'Upload Image'}
                        </Button>
                    )}
                />
                <RichText
                    tagName="h3"
                    value={attributes.name}
                    onChange={(value) => setAttributes({ name: value })}
                    placeholder="Enter name"
                />
                <RichText
                    tagName="h4"
                    value={attributes.position}
                    onChange={(value) => setAttributes({ position: value })}
                    placeholder="Enter position"
                />
                <Button onClick={() => setIsVisible(!isVisible)} className="button">
                    {isVisible ? 'Hide Details' : 'View Details'}
                </Button>
                {isVisible && (
                    <RichText
                        tagName="p"
                        value={attributes.description}
                        onChange={(value) => setAttributes({ description: value })}
                        placeholder="Enter description"
                    />
                )}
            </div>
        );
    },
    
    save: (props) => {
        const { attributes } = props;
        return (
            <div className="team-member">
                {attributes.imageUrl && <img src={attributes.imageUrl} alt="Profile" />}
                <h3>{attributes.name}</h3>
                <h4>{attributes.position}</h4>
                <button className="view-details" onClick={(e) => {
                    const desc = e.target.nextElementSibling;
                    desc.style.display = desc.style.display === 'block' ? 'none' : 'block';
                    e.target.textContent = desc.style.display === 'block' ? 'Hide Details' : 'View Details';
                }}>
                    View Details
                </button>
                <p style={{ display: 'none' }}>{attributes.description}</p>
            </div>
        );
    }
});
